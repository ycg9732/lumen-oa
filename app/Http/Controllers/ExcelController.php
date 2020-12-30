<?php


namespace App\Http\Controllers;

use Maatwebsite\Excel\Concerns\FromArray;

class ExcelController implements FromArray
{
    protected $invoices;
    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }
    public function array(): array
    {
        return $this->invoices;
    }
}
