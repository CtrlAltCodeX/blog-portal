<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\IssueType;
use App\Models\ComplaintUser;
use App\Models\Complaint;
use App\Models\ComplaintOrder;
use App\Models\ComplaintAttachment;
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
        Mail::to($user->email)->send(new ComplaintOtpMail($otp, $user));

        // Keep it flashed for testing if needed
        session()->flash('test_otp', $otp);

        return back()->with('success', 'OTP sent to your email.')->with('email_sent', true)->with('email', $request->email);
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

        return redirect()->route('public.complaints.create');
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
            'orders' => 'required|array|min:1',
            'orders.*.order_id' => 'required|string',
            'orders.*.ref_no' => 'required|string',
            'orders.*.tracking_id' => 'required|string',
            'orders.*.cx_name' => 'required|string',
            'orders.*.cx_phone' => 'required|string',
            'orders.*.loss_value' => 'required|numeric',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,xlsx,xls|max:5120'
        ]);

        $complaint_id = 'CMP-' . date('Ymd') . '-' . rand(1000, 9999);

        $complaint = Complaint::create([
            'complaint_id' => $complaint_id,
            'user_id' => session('complaint_user_id'),
            'issue_type_id' => $request->issue_type_id,
            'department_id' => $request->department_id,
            'title' => $request->title,
            'description' => $request->description,
            'delivery_timeline' => '3 Days',
            'specific_tag' => $request->specific_tag,
            'employee_name' => $request->employee_name,
            'employee_email' => $request->employee_email,
            'employee_mobile' => $request->employee_mobile,
            'send_mail' => $request->send_mail,
            'status' => 'pending'
        ]);

        // Save Orders
        foreach ($request->orders as $orderData) {
            $complaint->orders()->create($orderData);
        }

        // Handle Attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('complaints/attachments', 'public');
                $complaint->attachments()->create(['file_path' => $path]);
            }
        }

        // Clear verification session after success
        session()->forget('complaint_user_id');

        return redirect()->route('public.complaints.success', ['ticket_id' => $complaint_id]);
    }

    public function success($ticket_id)
    {
        return view('public.complaints.success', compact('ticket_id'));
    }
}
