<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ReportPerMonthSheet implements FromCollection, WithTitle, WithMapping, WithHeadings
{
    private $monthname;
    private $taxentries;
    private $adminEntries;

    public function __construct( $monthname, $taxentries, $adminEntries)
    {
        $this->monthname = $monthname;
        $this->taxentries  = $taxentries;
        $this->adminEntries  = $adminEntries;
    }

    public function collection()
    {
        return $this->taxentries;
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            Date::dateTimeToExcel($invoice->created_at),
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->monthname;
    }
    public function headings(): array
    {
        return [
            '#',
            'Date',
        ];
    }
}
