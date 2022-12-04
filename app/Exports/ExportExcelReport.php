<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportExcelReport implements WithMultipleSheets
{
    use Exportable;

    protected $data;

    protected $adminEntriesArray;

    protected $type;

    protected $months;

    public function __construct($data, $adminEntriesArray, $months)
    {
        $this->data = $data;
        $this->adminEntriesArray = $adminEntriesArray;
        $this->months = $months;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        //filename  $year . '-'. implode( '-', $monthnames )

        $monthnames = [];
        foreach ($this->months as $month) {
            $monthname = Carbon::createFromDate(2019, (int) $month, 1)->format('F');

            $monthnames[] = $monthname;
        }

        for ($index = 0, $month = (int) ($this->months[0]); $month <= (int) ($this->months[2]); $month++, $index++) {
            $taxentries = $this->data[$index];
            $adminEntries = $this->adminEntriesArray[$index];
            $sheets[] = new ReportPerMonthSheet($monthnames[$index], $taxentries, $adminEntries);
        }

        return $sheets;
    }
}
