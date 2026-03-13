<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\ComplaintReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminComplaintController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $query = Complaint::with(['user', 'issueType', 'department']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $complaints = $query->latest()->get();

        $counts = [
            'pending' => Complaint::where('status', 'pending')->count(),
            'verification' => Complaint::where('status', 'verification')->count(),
            'solved' => Complaint::where('status', 'solved')->count(),
            'mercy' => Complaint::where('status', 'mercy')->count(),
            'recovered' => Complaint::where('status', 'recovered')->count(),
            'all' => Complaint::count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'counts', 'status'));
    }

    public function show($id)
    {
        $complaint = Complaint::with(['user', 'issueType', 'department', 'orders', 'attachments', 'replies.user', 'replies.attachments'])->findOrFail($id);
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
            'message' => $request->message
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
