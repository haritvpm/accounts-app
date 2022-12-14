<?php

namespace App\Services;

use Illuminate\Support\Str;

class Extract
{
    const UNKNOWN = 'UNKNOWN';

    const SALARYBILL = 'SALARYBILL';

    const SALARYBILLMULTIPLE = 'SALARYBILLMULTIPLE';

    const FESTIVALALLOWANCE = 'FESTIVALALLOWANCE';

    const OVERTIME_ALLOWANCE = 'OVERTIME_ALLOWANCE';

    const BONUS = 'BONUS';

    const ONAMADVANCE = 'ONAMADVANCE';

    const DA_ARREAR = 'DA_ARREAR';

    const PAY_ARREAR = 'PAY_ARREAR';

    const SURRENDER = 'SURRENDER';

    const MR = 'MR';

    const SPARK_ID_PAY = 'SPARK_ID_PAY';

    const DECEASED = 'DECEASED';

    public array $pens;

    public array $errors;

    public string $acquittance;

    public string $sparkcode;

    public array $innerlines;

    public $tds_rows_only;

    public $has_it;

    public function __construct()
    {
        $this->pens = [];
        $this->errors = [];
        $this->acquittance = '';
        $this->sparkcode = '';
        $this->innerlines = [];
    }

    public static function getSparkCode($no_lattice, &$sparkcode)
    {
        $nolattice_lines = explode("\r\n", $no_lattice);
        for ($i = count($nolattice_lines) - 1; $i >= 0; $i--) {
            $l = $nolattice_lines[$i];

            if (false !== stripos($l, 'Spark Code :')) {
                $sparkcode = substr($l, strlen('Spark Code :') + stripos($l, 'Spark Code :'));

                $sparkcode = strtok($sparkcode, ',');

                $sparkcode = trim($sparkcode, '\'" ,');

                if (! str_contains($sparkcode, ' ')) {
                    $sparkcode = substr($sparkcode, 0, 20); //remove page number
                    $sparkcode = chunk_split($sparkcode, 5, ' '); //insert space every 5th position
                }
                $sparkcode = trim($sparkcode, ' ');

                break;
            }
        }
    }

    /*
    if line is separated by \r, $col will have a value. otherwise -1
    */
    public function getTitle($acquittance, $col = -1)
    {
        //PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,

        if (-1 == $col) {
            $acquittance = strstr($acquittance, ',', true);
        } else {
            $arr = explode("\r", $acquittance);
            $acquittance = strtok($arr[$col], ',');
            $acquittance = trim($acquittance, '\'"'); // any combination of ' and "
        }

        $acquittance = str_replace('IN RESPECT OF', '-', $acquittance);
        $acquittance = str_replace('THE MONTH OF', '', $acquittance);
        $acquittance = str_replace('FOR THE PERIOD', '', $acquittance);
        $acquittance = str_replace('PAYBILL OF', '', $acquittance);

        return $acquittance;
    }

    public function getpdfType(&$start)
    {
        $start = 0;

        $type = self::UNKNOWN;
        $this->sparkcode = '';

        for ($i = 0; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i];

            if (str_starts_with($l, 'Spark Code :')) {
                $this->getSparkCode($l, $this->sparkcode);
            }

            if ($type !== self::UNKNOWN && strlen($this->sparkcode)) {
                break; //already found a type. no need to parse fully
            }

            if ($type !== self::UNKNOWN) {
                continue; //already found a type. no need to parse fully
            }

            if (str_starts_with($l, 'GOVERNMENT OF KERALA')) {
                //PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,

                if (str_contains($this->innerlines[$i + 3], 'PAY AND ALLOWANCE IN RESPECT OF')) {
                    $type = self::SALARYBILL;
                    $start = $i;
                    if (str_contains($this->innerlines[$start + 3], ' PERIOD ')) {
                        $type = self::SALARYBILLMULTIPLE;
                    }

                    //PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,
                    $this->acquittance = $this->getTitle($this->innerlines[$i + 3]);

                    continue;
                }

                if (str_contains($this->innerlines[$i + 3], 'PAYBILL OF FESTIVAL ADVANCE IN RESPECT OF')) {
                    $type = self::ONAMADVANCE;
                    $start = $i + 3;

                    //PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,
                    $this->acquittance = $this->getTitle($this->innerlines[$i + 3]);

                    continue;
                }
            }

            if (strpos($l, 'FESTIVAL ALLOWANCE IN RESPECT OF') > 50) {
                $type = self::FESTIVALALLOWANCE;

                //.....\rFESTIVAL ALLOWANCE IN RESPECT OF MLA HOSTEL FOR THE MONTH OF September 2022",
                $this->acquittance = $this->getTitle($l, 4);
                $start = $i;

                continue;
            }
            if (strpos($l, 'AD HOC BONUS IN RESPECT OF') > 50) {
                $type = self::BONUS;

                //.....\rAD HOC BONUS IN RESPECT OF MLA HOSTEL FOR THE MONTH OF September 2022,
                $this->acquittance = $this->getTitle($l, 4);
                $start = $i;

                continue;
            }
            if (strpos($l, 'PAY AND ALLOWANCE IN RESPECT') > 50 &&
                str_contains($this->innerlines[$i + 2], 'Overtime')) {
                $type = self::OVERTIME_ALLOWANCE;

                //.....\rPAY AND ALLOWANCE IN RESPECT OF MLA HOSTEL FOR September 2022",

                $start = $i;
                $this->acquittance = $this->getTitle($l, 3);
                $this->acquittance = str_replace('PAY AND ALLOWANCE', 'OVERTIME ALLOWANCE', $this->acquittance);

                continue;
            }
            if (str_starts_with($l, 'Due and drawn statement cum D.A. ARREAR')) {
                $type = self::DA_ARREAR;
                $this->acquittance = $this->getTitle($l);

                $start = $i;
            }
            if (str_starts_with($l, 'Due and drawn statement cum PAY')) {
                $type = self::PAY_ARREAR;
                $this->acquittance = $this->getTitle($l);
                $start = $i + 1; //on more column than DA arrear statement to the header
            }

            if (str_starts_with($l, 'SURRENDER LEAVE SALARY')) {
                $type = self::SURRENDER;
                $this->acquittance = $this->getTitle($l);
                $start = $i + 1;
            }

            if (str_starts_with($l, 'NATURE OF CLAIM-,Medical Reimbursement Bill')) {
                $type = self::MR;
                $this->acquittance = 'MEDICAL REIMBURSEMENT';
                $start = $i + 1;
            }

            if (str_starts_with($l, 'NATURE OF CLAIM-,Pay and Allowance for Employees with SPARK ID')) {
                $type = self::SPARK_ID_PAY;
                $this->acquittance = 'Pay and Allowance for Employees with SPARK ID';
                $start = $i + 1;
            }
            if (str_starts_with($l, 'NATURE OF CLAIM-,Salary of deceased employees to Nominees')) {
                $type = self::DECEASED;
                $this->acquittance = 'Salary of deceased employees to Nominees';
                $start = $i + 24;  //skip to header with bank data.
            }
        }

        return $type;
    }

    public function findColumnIndex($start, &$grosscol, &$it_col, $fieldGross = 'Gross Salary', $fieldIT = 'IT')
    {
        $grosscol = -1;
        $it_col = -1;
        $ITColNames = ['IT', 'INCOME TAX DEDUCTION', 'Income tax to be deducted'];

        for ($i = $start + 1; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i];
            $heading = str_replace("\r", ' ', $l);

            $cols = str_getcsv($heading);

            for ($j = 0; $j < count($cols); $j++) {
                if (strcmp($cols[$j], $fieldGross) === 0) { //this will automatically find the last matching column if there are multiple 'total' cols like in lkeave surrender
                    $grosscol = $j;
                }
                if (strcmp($cols[$j], $fieldIT) === 0) {
                    $it_col = $j;
                }
            }
            if (! $this->has_it) {
                for ($j = 0; $j < count($ITColNames); $j++) {
                    if (in_array($ITColNames[$j], $cols)) {
                        $this->errors[] = 'has a column for IT: '.$ITColNames[$j];

                        return false;
                    }
                }
            }

            if (-1 !== $grosscol) {
                break;
            }
        }

        // dd($cols);
        if (-1 == $grosscol) {
            $this->errors[] = 'Unable to determine column for '.$fieldGross;

            return false;
        }

        if ($this->has_it && -1 == $it_col) {
            $this->errors[] = 'Unable to determine column for '.$fieldIT;

            return false;
        }

        return true;
    }

    public function processSalaryBill($start)
    {
        $i = $start;
        $i += 5;

        if (! $this->findColumnIndex($start, $grosscol, $it_col)) {
            return [];
        }

        // $out->writeln($grosscol);
        $data = [];

        $slno = 1;
        $tds_total = 0;
        for (; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i];
            $slnotxt = sprintf('%u,', $slno);
            //  $out->writeln($slnotxt);

            if (str_starts_with($l, $slnotxt)) {
                $slno++;
                $cols = str_getcsv($l);

                //"821472 ( 683/2017 )NAVEENJAMES NORONA -Revised"
                $penname = str_replace("\r", ' ', $cols[1]);

                $pen = strstr($penname, ' ', true);

                $name = Str::of($penname)->after(' ')->before('-')->after(')')->trim();

                /*  if($slno == 90){
                     dd($pen .'^'. $name);
                 } */

                $tds = '0';

                if ($this->has_it) {
                    $tds = $cols[$it_col];
                    $tds_total += $cols[$it_col];

                    if (intval($tds) == 0 && $this->tds_rows_only) {
                        continue;
                    }
                }

                $items = [
                    'slno' => $slno - 1,
                    'pen' => $pen,
                    'name' => $name,
                    'gross' => $cols[$grosscol],
                    'tds' => $tds,

                ];

                $this->pens[] = $pen;

                $data[] = $items;
            }
        }

        //verify tds with total
        if ($this->has_it) {
            for ($r = count($this->innerlines) - 1; $it_col !== -1 && $r >= 0; $r--) {
                $totalline = $this->innerlines[$r];
                if (str_starts_with($totalline, 'Total')) {
                    $cols = str_getcsv($totalline);
                    $total_as_per_sheet = $cols[$it_col - 1]; //sl.no and name cols merged into one, so one before
                    if ($tds_total != $total_as_per_sheet) {
                        $this->errors[] = 'Unable to verify total tds'.$tds_total.'-'.$total_as_per_sheet;

                        return [];
                    }

                    break;
                }
            }
        }

        return  $data;
    }

    public function processpdftext($inner, $tds_rows_only, $has_it)
    {
        // $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        $this->innerlines = explode("\r\n", $inner);
        $this->tds_rows_only = $tds_rows_only;
        $this->has_it = $has_it;

        $start = 0;
        $type = $this->getpdfType($start);

        switch($type) {
            case self::SALARYBILL:
            case self::ONAMADVANCE:
                return $this->processSalaryBill($start);
            case self::FESTIVALALLOWANCE:
                return $this->processFestivalAllowance($start, 'Festival Allowance');
            case self::OVERTIME_ALLOWANCE:
                return $this->processFestivalAllowance($start, 'Overtime Duty Allowance');
            case self::BONUS:
                return $this->processFestivalAllowance($start, 'Bonus');
            case self::DA_ARREAR:
            case self::PAY_ARREAR:
                return $this->processDaArrear($start);
            case self::SALARYBILLMULTIPLE:
                return $this->processDaArrear($start, 'Gross Salary', 'IT', 1);
            case self::SURRENDER:
                return $this->processFestivalAllowance($start, 'TOTAL', 'INCOME TAX DEDUCTION');
            case self::MR:
                return $this->processMedical($start, 'Amount `');
            case self::SPARK_ID_PAY:
                return $this->processMedical($start, 'Net Amount`', 'IT');
            case self::DECEASED:
                return $this->processDeceased($start, 'Amount`');
        }

        return [];
    }

    public function processFestivalAllowance($start, $fieldGross, $fieldIT = 'IT')
    {
        $i = $start;
        $i += 2;

        if (! $this->findColumnIndex($start, $grosscol, $it_col, $fieldGross, $fieldIT)) {
            return [];
        }

        $data = [];

        $slno = 1;
        $tds_total = 0;
        for (; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i];
            $slnotxt = sprintf('%u,', $slno);
            //  $out->writeln($slnotxt);

            if (str_starts_with($l, $slnotxt)) {
                $slno++;
                $cols = str_getcsv($l);

                $pen = trim($cols[1]); //remove any hyphen 'revised'
                $name = trim($cols[2]); //remove any hyphen 'revised'

                $tds = '0';

                if ($this->has_it) {
                    $tds = $cols[$it_col];
                    $tds_total += $cols[$it_col];

                    if (intval($tds) == 0 && $this->tds_rows_only) {
                        continue;
                    }
                }

                $items = [
                    'slno' => $slno - 1,
                    'pen' => $pen,
                    'name' => $name,
                    'gross' => $cols[$grosscol],
                    'tds' => $tds,

                ];

                $this->pens[] = $pen;

                $data[] = $items;
            }
        }

        return  $data;
    }

    ////
    public static function IsPEN($pen)
    {
        return  (strlen($pen) >= 6) && (strlen($pen) <= 8) &&
                ((int) filter_var($pen, FILTER_SANITIZE_NUMBER_INT) >= 10000) && //should have a number inside exlcuding chars
                false === strpos($pen, ' ');
    }

    /*
    $coloffset = How many columns are merged to show 'Total' minus one. For DA, PAY it is 3. For Multiple Month Salary, it is 2

    */
    public function processDaArrear($start, $fieldgross = 'Total', $fieldIT = 'Income tax to be deducted',
                                    $coloffset = 2)
    {
        $i = $start;
        $i += 4;

        if (! $this->findColumnIndex($start, $grosscol, $it_col, $fieldgross, $fieldIT)) {
            return [];
        }

        $data = [];

        $slno = 1;
        $totalarrear = 0;
        $totaltds = 0;

        for (; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i]; //"176624 Anil Kumar B , Office Superintendent",,,,,,,,,,,,,,,,,,,,,
            $cols = str_getcsv($l);

            //last line
            if (0 === strcmp($cols[0], 'Grand Total(in figures)')) {
                if ($cols[$grosscol - $coloffset] != $totalarrear) {
                    $this->errors[] = 'Grand Total and individual sum dont match';

                    return [];
                }
                if ($this->has_it && $cols[$it_col - $coloffset] != $totaltds) {
                    $this->errors[] = 'Grand Total TDS and individual sum dont match';

                    return [];
                }
                break;
            }

            $penname = explode(',', $cols[0])[0];

            $pen = trim(strstr($penname, ' ', true)); //remove any hyphen 'revised'
            $name = trim(strstr($penname, ' ')); //remove any hyphen 'revised'

            if (Extract::IsPEN($pen)) { //it is a PEN
                //find next 'total' line
                for (; $i < count($this->innerlines); $i++) {
                    $l = $this->innerlines[$i];
                    $cols = str_getcsv($l);

                    if ($cols[0] == 'Total') {
                        $gross = $cols[$grosscol - $coloffset]; // first two cols are merged

                        $tds = '0';

                        if ($this->has_it) {
                            $tds = $cols[$it_col - $coloffset]; // first two cols are merged
                            if (intval($tds) == 0 && $this->tds_rows_only) {
                                continue;
                            }
                            $totaltds += $tds;
                        }

                        $items = [
                            'slno' => $slno++,
                            'pen' => $pen,
                            'name' => $name,
                            'gross' => $gross,
                            'tds' => $tds,

                        ];

                        $this->pens[] = $pen;
                        $data[] = $items;
                        $totalarrear += $gross;

                        break;
                    }
                }
            }
        }

//        dd( $data);

        return  $data;
    }

    public function processMedical($start, $fieldGross, $fieldIT = 'IT')
    {
        $i = $start;
        $i += 2;

        if (! $this->findColumnIndex($start, $grosscol, $it_col, $fieldGross, $fieldIT)) {
            return [];
        }

        $data = [];

        $slno = 1;
        $tds_total = 0;
        for (; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i];
            $slnotxt = sprintf('%u,', $slno);
            //  $out->writeln($slnotxt);

            if (str_starts_with($l, $slnotxt)) {
                $slno++;
                $cols = str_getcsv($l);

                $pen = trim($cols[2]);
                $name = trim($cols[1]);

                $tds = '0';

                if ($this->has_it) {
                    $tds = $cols[$it_col];
                    $tds_total += $cols[$it_col];

                    if (intval($tds) == 0 && $this->tds_rows_only) {
                        continue;
                    }
                }

                $items = [
                    'slno' => $slno - 1,
                    'pen' => $pen,
                    'name' => $name,
                    'gross' => $cols[$grosscol],
                    'tds' => $tds,

                ];

                $this->pens[] = $pen;

                $data[] = $items;
            }
        }
        // dd($data);
        return  $data;
    }

    public function processDeceased($start, $fieldGross, $fieldIT = 'IT')
    {
        if (! $this->findColumnIndex($start, $grosscol, $it_col, $fieldGross, $fieldIT)) {
            return [];
        }

        $data = [];

        $slno = 0;
        $tds_total = 0;

        $i = $start + 2; //next line after header

        for (; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i];

            if (str_starts_with($l, 'Payees')) {
                break;
            }

            $slno++;
            $cols = str_getcsv($l);

            $account = trim($cols[$grosscol - 2]);
            $name = trim($cols[0]);

            $tds = '0';

            if ($this->has_it) {
                $tds = $cols[$it_col];
                $tds_total += $cols[$it_col];

                if (intval($tds) == 0 && $this->tds_rows_only) {
                    continue;
                }
            }

            $items = [
                'slno' => $slno,
                'pen' => $account,
                'name' => $name,
                'gross' => $cols[$grosscol],
                'tds' => $tds,

            ];

            $this->pens[] = $account;

            $data[] = $items;
        }
        //  dd($data);
        return  $data;
    }
}
