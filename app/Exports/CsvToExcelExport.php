<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class CsvToExcelExport implements FromCollection,WithTitle,WithHeadings,ShouldAutoSize, WithEvents
{
    public $data;

    public $title;

    public function __construct($title, $data)
    {
        $this->data = $data;
        $this->title= $title;
    }

    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings():array
    {
        return array_keys($this->data['count']);
    }

     public function registerEvents(): array
     {
         return [
             AfterSheet::class    => function(AfterSheet $event) {
                 $cellRange = 'A1:W1'; // All headers
                 $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
             },
         ];
     }
}
