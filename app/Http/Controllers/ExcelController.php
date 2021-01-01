<?php


namespace App\Http\Controllers;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\BaseDrawing;

class ExcelController implements FromArray,WithColumnFormatting,WithColumnWidths,WithDrawings
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
          'A'=>'15',
          'B'=>'15',
          'C'=>'15',
          'D'=>'15',
        ];
    }

    public function drawings()
    {
        return $this->drawings;
    }
}
