<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BulkUploadListingsImport implements ToCollection
{

    protected $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function collection(Collection $rows)
    {
        // Use the first row as headers
        $headers = $rows->shift()->toArray();

        foreach ($rows as $row) {
            $this->data[] = array_combine($headers, $row->toArray());
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
