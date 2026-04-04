<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\IssueType;
use App\Models\ComplaintUser;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComplaintOtpMail;

class PublicComplaintController extends Controller
{
    public function startVerification()
    {
        session()->forget('complaint_user_id');
        return redirect()->route('public.complaints.create');
    }

    public function create()
    {
        if (!session()->has('complaint_user_id')) {
            return view('public.complaints.verify');
        }

        $issueTypes = IssueType::where('status', 1)->get();
        $departments = Department::where('status', 1)->get();
        $verifiedUser = ComplaintUser::find(session('complaint_user_id'));

        return view('public.complaints.create', compact('issueTypes', 'departments', 'verifiedUser'));
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = ComplaintUser::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found in our records.');
        }

        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send Email
        // Mail::to($user->email)->send(new ComplaintOtpMail($otp, $user));

        // Keep it flashed for testing if needed
        session()->flash('test_otp', $otp);

        return back()->with('success', 'OTP sent to your email.')
            ->with('email_sent', true)
            ->with('email', $request->email);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $user = ComplaintUser::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->with('error', 'Invalid or expired OTP.')->with('email_sent', true)->with('email', $request->email);
        }

        session(['complaint_user_id' => $user->id]);
        $user->update(['otp' => null, 'otp_expires_at' => null]);

        return redirect()->route('public.complaints.dashboard');
    }

    public function dashboard()
    {
        if (!session()->has('complaint_user_id')) {
            return redirect()->route('public.complaints.create');
        }

        return view('public.complaints.dashboard');
    }

    public function index(Request $request)
    {
        if (!session()->has('complaint_user_id')) {
            return redirect()->route('public.complaints.create');
        }

        $userId = session('complaint_user_id');
        $status = $request->get('status', 'pending');

        $query = Complaint::where('complaint_user_id', $userId)->with(['issueType', 'department', 'complaint_user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('complaint_id', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $complaints = $query->latest()->get();

        $counts = [
            'pending' => Complaint::where('complaint_user_id', $userId)->where('status', 'pending')->count(),
            'verification' => Complaint::where('complaint_user_id', $userId)->where('status', 'verification')->count(),
            'solved' => Complaint::where('complaint_user_id', $userId)->where('status', 'solved')->count(),
            'mercy' => Complaint::where('complaint_user_id', $userId)->where('status', 'mercy')->count(),
            'recovered' => Complaint::where('complaint_user_id', $userId)->where('status', 'recovered')->count(),
            'all' => Complaint::where('complaint_user_id', $userId)->count(),
        ];

        return view('public.complaints.index', compact('complaints', 'counts', 'status'));
    }

    public function show($id)
    {
        if (!session()->has('complaint_user_id')) {
            return redirect()->route('public.complaints.create');
        }

        $userId = session('complaint_user_id');

        $complaint = Complaint::where('complaint_user_id', $userId)
            ->with(['issueType', 'department', 'orders', 'attachments', 'replies.user', 'replies.attachments', 'complaint_user'])
            ->findOrFail($id);

        return view('public.complaints.show', compact('complaint'));
    }

    public function storeReply(Request $request, $id)
    {
        if (!session()->has('complaint_user_id')) {
            return redirect()->route('public.complaints.create');
        }

        $userId = session('complaint_user_id');
        $complaint = Complaint::where('complaint_user_id', $userId)->findOrFail($id);

        $request->validate([
            'message' => 'required|string',
            'status' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120'
        ]);

        $reply = \App\Models\ComplaintReply::create([
            'complaint_id' => $complaint->id,
            'complaint_user_id' => $userId,
            'message' => $request->message,
            'status' => $request->status
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('complaints/replies', 'public');
                $reply->attachments()->create(['file_path' => $path]);
            }
        }

        // Apply status transition from request (matching admin logic)
        $complaint->update(['status' => $request->status]);

        return back()->with('success', 'Reply submitted successfully.');
    }


    public function store(Request $request)
    {
        $request->validate([
            'issue_type_id' => 'required|exists:issue_types,id',
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:1000',
            'description' => 'required|string',
            'specific_tag' => 'required|boolean',
            'send_mail' => 'required|boolean',
            'orders' => 'nullable|array',
            'orders.*.order_id' => 'nullable|string',
            'orders.*.ref_no' => 'nullable|string',
            'orders.*.tracking_id' => 'nullable|string',
            'orders.*.cx_name' => 'nullable|string',
            'orders.*.cx_phone' => 'nullable|string',
            'orders.*.loss_value' => 'nullable|numeric',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,xlsx,xls|max:5120',
            'delivery_timeline' => 'required|integer|min:1|max:7',
            'managed_by' => 'required|string|in:self with admin,admin'
        ]);

        $today = date('dmY');
        $count = Complaint::whereDate('created_at', now()->toDateString())->count() + 1;
        $complaint_id = 'charge-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $complaint = Complaint::create([
            'complaint_id' => $complaint_id,
            'complaint_user_id' => session('complaint_user_id'),
            'issue_type_id' => $request->issue_type_id,
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
            'delivery_timeline' => $request->delivery_timeline . ($request->delivery_timeline == 1 ? ' Day' : ' Days'),
            'managed_by' => $request->managed_by,
            'specific_tag' => $request->specific_tag,
            'employee_name' => $request->employee_name,
            'employee_email' => $request->employee_email,
            'employee_mobile' => $request->employee_mobile,
            'send_mail' => $request->send_mail,
            'status' => 'pending'
        ]);

        // Save Orders (Filter out completely empty rows)
        if ($request->has('orders')) {
            foreach ($request->orders as $orderData) {
                // Check if any field has content
                $hasData = array_filter($orderData, fn($value) => !is_null($value) && $value !== '');
                if (!empty($hasData)) {
                    $complaint->orders()->create($orderData);
                }
            }
        }

        // Handle Attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('complaints/attachments', 'public');
                $complaint->attachments()->create(['file_path' => $path]);
            }
        }

        // session()->forget('complaint_user_id'); // Keep session to allow dashboard access after success

        return redirect()->route('public.complaints.success', ['ticket_id' => $complaint_id]);
    }

    public function success($ticket_id)
    {
        return view('public.complaints.success', compact('ticket_id'));
    }
}