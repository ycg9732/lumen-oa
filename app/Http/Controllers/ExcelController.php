<?php


namespace App\Http\Controllers;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ExcelController implements FromArray,WithColumnFormatting,WithColumnWidths
{
    protected $arr;
    public function __construct(array $arr)
    {
        $this->arr= $arr;
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
          'A'=>'50',
          'B'=>'50',
          'C'=>'50',
          'D'=>'50',
        ];
    }
}
