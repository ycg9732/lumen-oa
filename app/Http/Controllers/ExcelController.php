<?php


namespace App\Http\Controllers;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\BaseDrawing;

class ExcelController implements FromArray,WithColumnFormatting,WithColumnWidths,WithDrawings,WithEvents
{
    protected $arr;
    protected $drawings;
    public function __construct(array $arr,$drawings)
    {
        $this->arr= $arr;
        $this->drawings = $drawings;
    }
    public function array(): array
    {
        return $this->arr;
    }

    public function columnFormats(): array
    {
//        return [
//            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
//            'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
//        ];
        return [];
    }

    public function columnWidths(): array
    {
        return [
          'A'=>'20',
          'B'=>'20',
          'C'=>'20',
          'D'=>'20',
          'E'=>'20',
          'F'=>'20',
          'G'=>'20',
          'H'=>'20',
          'I'=>'20',
          'J'=>'20',
          'K'=>'20',
          'L'=>'20',
          'M'=>'20',
          'N'=>'20',
        ];
    }

    public function drawings()
    {
        return $this->drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getDelegate()->getDefaultRowDimension()->setRowHeight(50);
            },
        ];
    }
}
