<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SegmentExport implements WithMultipleSheets
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        foreach ($this->data as $key => $value){
            $collection = collect($value);
            $sheets[] = new CsvToExcelExport($key,$collection);
        }

        return $sheets;
    }
}
