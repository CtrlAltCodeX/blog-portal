<?php

namespace App\Exports;

use App\Models\CandidateEnquiry;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CandidateEnquiriesExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;
    protected $columns;

    public function __construct($request)
    {
        $this->request = $request;
        $this->columns = Schema::getColumnListing('candidate_enquiries');
    }

    public function query()
    {
        $q = CandidateEnquiry::with('user');

        // Status
        if ($this->request->status) {
            $q->where('status', $this->request->status);
        }

        // Preference
        if ($this->request->preference) {
            if ($this->request->preference !== 'Others') {
                $q->where('preference', 'like', '%' . $this->request->preference . '%');
            } else {
                $q->where(function ($sub) {
                    $sub->where('preference', 'not like', '%Work from Home%')
                        ->where('preference', 'not like', '%Work from Office%');
                });
            }
        }

        // Date Filter
        if ($this->request->created_start_date && $this->request->created_end_date) {
            $q->whereBetween('created_at', [
                $this->request->created_start_date . ' 00:00:00',
                $this->request->created_end_date . ' 23:59:59'
            ]);
        }

        if ($this->request->updated_start_date && $this->request->updated_end_date) {
            $q->whereBetween('updated_at', [
                $this->request->updated_start_date . ' 00:00:00',
                $this->request->updated_end_date . ' 23:59:59'
            ]);
        }

        // Search
        if ($this->request->search) {
            $q->where(function ($query) {
                $query->where('name', 'like', '%' . $this->request->search . '%')
                    ->orWhere('email', 'like', '%' . $this->request->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->request->search . '%')
                    ->orWhere('address', 'like', '%' . $this->request->search . '%')
                    ->orWhere('preference', 'like', '%' . $this->request->search . '%');
            });
        }

        return $q->orderBy(
            'created_at',
            $this->request->order_by ?? 'desc'
        );
    }

    /**
     * Headings (replace user_id with User Name)
     */
    public function headings(): array
    {
        return array_map(function ($column) {
            if ($column === 'user_id') {
                return 'Moved By';
            }
            return ucwords(str_replace('_', ' ', $column));
        }, $this->columns);
    }

    /**
     * Map row values (replace user_id value with user name)
     */
    public function map($row): array
    {
        $data = [];

        foreach ($this->columns as $column) {

            if ($column === 'user_id') {
                $data[] = optional($row->user)->name; // ðŸ”¥ user name instead of id
                continue;
            }

            $value = $row->{$column};

            if ($value instanceof \Carbon\Carbon) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $data[] = $value;
        }

        return $data;
    }
}
