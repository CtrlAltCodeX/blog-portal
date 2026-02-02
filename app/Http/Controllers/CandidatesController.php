<?php

namespace App\Http\Controllers;

use App\Exports\CandidateEnquiriesExport;
use App\Models\CandidateEnquiry;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class CandidatesController extends Controller
{
    public function enquiries(Request $request)
    {
        $allEnquiries = $this->getEnquiries();
        $newEnquiries = $this->getEnquiries('New Candidate');
        $activeEnquiries = $this->getEnquiries('Successful Candidate');
        $inactiveEnquiries = $this->getEnquiries('Un-Successful Candidate');

        return view('candidates.enquiries', compact('newEnquiries', 'activeEnquiries', 'inactiveEnquiries', 'allEnquiries'));
    }

    public function getEnquiries($status = NULL)
    {
        $orderBy = request()->get('order_by', 'desc');

        $orderBy = in_array(strtolower($orderBy), ['asc', 'desc'])
            ? $orderBy
            : 'desc';

        $enquiries = CandidateEnquiry::with('user')
            ->orderBy('created_at', $orderBy);

        if ($status) $enquiries->where('status', $status);

        $enquiries->when(request()->preference, function ($q) {
            if (request()->preference !== 'Others') {
                $q->where('preference', 'like', '%' . request()->preference . '%');
            } else {
                $q->where(function ($sub) {
                    $sub->where('preference', 'not like', '%Work from Home%')
                        ->where('preference', 'not like', '%Work from Office%');
                });
            }
        });

        if (request()->search) {
            $enquiries->where(function ($query) {
                $query->where('name', 'like', '%' . request()->search . '%')
                    ->orWhere('email', 'like', '%' . request()->search . '%')
                    ->orWhere('phone', 'like', '%' . request()->search . '%')
                    ->orWhere('address', 'like', '%' . request()->search . '%')
                    ->orWhere('preference', 'like', '%' . request()->search . '%');
            });
        }

        // ðŸ”¥ Start & End Date Filter
        $enquiries->when(request()->created_start_date || request()->created_end_date, function ($q) {
            if (request()->created_start_date && request()->created_end_date) {
                $q->whereBetween('created_at', [
                    request()->created_start_date . ' 00:00:00',
                    request()->created_end_date . ' 23:59:59'
                ]);
            } elseif (request()->created_start_date) {
                $q->where('created_at', '>=', request()->created_start_date . ' 00:00:00');
            } elseif (request()->created_end_date) {
                $q->where('created_at', '<=', request()->created_end_date . ' 23:59:59');
            }
        });

        $enquiries->when(request()->updated_start_date || request()->updated_end_date, function ($q) {
            if (request()->updated_start_date && request()->updated_end_date) {
                $q->whereBetween('created_at', [
                    request()->updated_start_date . ' 00:00:00',
                    request()->updated_end_date . ' 23:59:59'
                ]);
            } elseif (request()->updated_start_date) {
                $q->where('updated_at', '>=', request()->updated_start_date . ' 00:00:00');
            } elseif (request()->updated_end_date) {
                $q->where('updated_at', '<=', request()->updated_end_date . ' 23:59:59');
            }
        });

        $enquiries = $enquiries->paginate(request()->paging ?? 25);

        return $enquiries;
    }

    public function updateStatus($id)
    {
        $status = request()->status;

        CandidateEnquiry::query()
            ->where('id', $id)
            ->update([
                'status' => $status,
                'user_id' => auth()->user()->id
            ]);

        return response()->json(['message' => 'Status updated.']);
    }

    public function saveNotes($id, Request $request)
    {
        $note = $request->note;

        CandidateEnquiry::query()
            ->where('id', $id)
            ->update(['notes' => $note]);

        return response()->json(['message' => 'Note saved successfully.']);
    }

    public function export()
    {
        return FacadesExcel::download(
            new CandidateEnquiriesExport(request()),
            'candidate_enquiries.xlsx'
        );
    }
}
