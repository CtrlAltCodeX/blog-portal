<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintUser;
use App\Models\ComplaintReply;
use App\Models\Department;
use App\Models\IssueType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminComplaintController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = Complaint::with(['user', 'issueType', 'department', 'complaint_user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('complaint_id', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('complaint_user_id')) {
            $query->where('complaint_user_id', $request->complaint_user_id);
        }

        $counts = [
            'pending' => Complaint::where('status', 'pending')->count(),
            'verification' => Complaint::where('status', 'verification')->count(),
            'solved' => Complaint::where('status', 'solved')->count(),
            'mercy' => Complaint::where('status', 'mercy')->count(),
            'recovered' => Complaint::where('status', 'recovered')->count(),
            'all' => Complaint::count(),
        ];

        $users = ComplaintUser::orderBy('name')->get();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $complaints = $query->latest()->get();

        return view('admin.complaints.index', compact('complaints', 'counts', 'status', 'users'));
    }

    public function create()
    {
        $issueTypes = IssueType::where('status', 1)
            ->get();

        $departments = Department::where('status', 1)
            ->get();

        return view('admin.complaints.create', compact('issueTypes', 'departments'));
    }

    public function store(Request $request)
    {
        try {
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
                // 'managed_by' => 'required|string|in:self with admin,admin'
            ]);

            $today = date('dmY');
            $count = Complaint::whereDate('created_at', now()->toDateString())->count() + 1;
            $complaint_id = 'charge-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $complaint = Complaint::create([
                'complaint_id' => $complaint_id,
                'user_id' => session('complaint_user_id'),
                'issue_type_id' => $request->issue_type_id,
                'department_id' => $request->department_id,
                'title' => $request->title,
                'description' => $request->description,
                'delivery_timeline' => $request->delivery_timeline . ($request->delivery_timeline == 1 ? ' Day' : ' Days'),
                // 'managed_by' => $request->managed_by,
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

            return redirect()->route('admin.complaints.index')->with('success', 'Complaint submitted successfully with ID: ' . $complaint_id);
        }
        catch (\Exception $e) {
            return back()->with('error', 'An error occurred while submitting the complaint: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $complaint = Complaint::with(['user', 'issueType', 'department', 'orders', 'attachments', 'replies.user', 'replies.attachments', 'complaint_user'])
            ->findOrFail($id);

        return view('admin.complaints.show', compact('complaint'));
    }

    public function storeReply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'status' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120'
        ]);

        $complaint = Complaint::findOrFail($id);

        $reply = ComplaintReply::create([
            'complaint_id' => $complaint->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'status' => $request->status
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('complaints/replies', 'public');
                $reply->attachments()->create(['file_path' => $path]);
            }
        }

        $complaint->update(['status' => $request->status]);

        return back()->with('success', 'Reply submitted and status updated.');
    }
}