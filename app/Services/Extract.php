<?php

namespace App\Services;

class Extract
{
    const UNKNOWN = 'UNKNOWN';

    const SALARYBILL = 'SALARYBILL';

    const SALARYBILLMULTIPLE = 'SALARYBILLMULTIPLE';

    const FESTIVALALLOWANCE = 'FESTIVALALLOWANCE';

    const OVERTIME_ALLOWANCE = 'OVERTIME_ALLOWANCE';

    const BONUS = 'BONUS';

    const ONAMADVANCE = 'ONAMADVANCE';

    public function __construct()
    {
    }

    public function getpdfType($innerlines, $no_lattice, &$start, &$acquittance, &$sparkcode)
    {
        $start = 0;

        $type = self::UNKNOWN;
        $sparkcode = '';

        for ($i = 0; $i < count($innerlines); $i++) {
            $l = $innerlines[$i];

            if (0 == strncmp($l, 'Spark Code :', strlen('Spark Code :'))) {
                $arr = explode('Spark Code :', $l);
                $sparkcode = strstr($arr[1], ',', true);
            }

            if ($type !== self::UNKNOWN && strlen($sparkcode)) {
                break; //already found a type. no need to parse fully
            }

            if ($type !== self::UNKNOWN) {
                continue; //already found a type. no need to parse fully
            }

            if (0 == strncmp($l, 'GOVERNMENT OF KERALA', strlen('GOVERNMENT OF KERALA'))) {
                //GET month between FOR and coma in: PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,

                if (false !== strpos($innerlines[$i + 3], 'PAY AND ALLOWANCE IN RESPECT OF')) {
                    $type = self::SALARYBILL;
                    $start = $i;
                    if (false !== strpos($innerlines[$start + 3], ' PERIOD ')) {
                        $type = self::SALARYBILLMULTIPLE;
                    }

                    //GET month between FOR and coma in: PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,
                    $acquittance = $innerlines[$i + 3];
                    $acquittance = strstr($acquittance, ',', true);
                    $acquittance = str_replace('IN RESPECT OF', '-', $acquittance);
                    $acquittance = str_replace('THE MONTH OF', '', $acquittance);

                    continue;
                }

                if (false !== strpos($innerlines[$i + 3], 'PAYBILL OF FESTIVAL ADVANCE IN RESPECT OF')) {
                    $type = self::ONAMADVANCE;
                    $start = $i + 3;

                    //PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,

                    $acquittance = $innerlines[$i + 3];
                    $acquittance = strstr($acquittance, ',', true);
                    $acquittance = str_replace('IN RESPECT OF', '-', $acquittance);
                    $acquittance = str_replace('THE MONTH OF', '', $acquittance);

                    continue;
                }
            }

            if (strpos($l, 'FESTIVAL ALLOWANCE IN RESPECT OF') > 50) {
                $type = self::FESTIVALALLOWANCE;

                //.....\rFESTIVAL ALLOWANCE IN RESPECT OF MLA HOSTEL FOR THE MONTH OF September 2022",
                $arr = explode("\r", $l);
                $start = $i;
                $acquittance = strstr($arr[4], ',', true);
                $acquittance = trim($acquittance, '\'"'); // any combination of ' and "
                $acquittance = str_replace('IN RESPECT OF', '-', $acquittance);
                $acquittance = str_replace('THE MONTH OF', '', $acquittance);

                continue;
            }
            if (strpos($l, 'AD HOC BONUS IN RESPECT OF') > 50) {
                $type = self::BONUS;

                //.....\rAD HOC BONUS IN RESPECT OF MLA HOSTEL FOR THE MONTH OF September 2022,
                $arr = explode("\r", $l);
                $start = $i;
                $acquittance = strstr($arr[4], ',', true);
                $acquittance = trim($acquittance, '\'"'); // any combination of ' and "
                $acquittance = str_replace('IN RESPECT OF', '-', $acquittance);
                $acquittance = str_replace('THE MONTH OF', '', $acquittance);

                continue;
            }
            if (strpos($l, 'PAY AND ALLOWANCE IN RESPECT') > 50 &&
                false !== strpos($innerlines[$i + 2], 'Overtime')) {
                $type = self::OVERTIME_ALLOWANCE;

                //.....\rPAY AND ALLOWANCE IN RESPECT OF MLA HOSTEL FOR September 2022",
                $arr = explode("\r", $l);
                $start = $i;

                $acquittance = strstr($arr[3], ',', true);
                $acquittance = trim($acquittance, '\'"'); // any combination of ' and "
                $acquittance = str_replace('PAY AND ALLOWANCE IN RESPECT OF ', 'OVERTIME ALLOWANCE - ', $acquittance);

                continue;
            }
        }

        if ($sparkcode == '') {
            for ($i = count($no_lattice) - 1; $i >= 0; $i--) {
                $l = $no_lattice[$i];

                if (false !== strpos($l, 'Spark Code :')) {
                    $sparkcode = substr($l, strlen('Spark Code :') + strpos($l, 'Spark Code :'));
                    $sparkcode = strstr($sparkcode, ',', true);
                    $sparkcode = trim($sparkcode, '\'" ,');
                    if (strpos($sparkcode, ' ') === false) {
                        $sparkcode = substr($sparkcode, 0, 20); //remove page number
                        $sparkcode = chunk_split($sparkcode, 5, ' '); //insert space every 5th position
                    }
                    $sparkcode = trim($sparkcode, ' ');

                    break;
                }
            }
        }

        return $type;
    }

    public function processSalaryBill($start, $innerlines, &$pens, &$errors, $tds_rows_only, $has_it)
    {
        $i = $start;
        $i += 5;
        $heading = $innerlines[$i];

        $cols = str_getcsv($heading);
        $grosscol = -1;
        $it_col = -1;
        for ($j = 0; $j < count($cols); $j++) {
            if (strpos($cols[$j], 'Gross') !== false) {
                $grosscol = $j;
            }
            if (strcmp($cols[$j], 'IT') === 0) {
                $it_col = $j;
            }
        }

        // dd($cols);
        if (-1 == $grosscol) {
            $errors[] = 'Unable to determine gross column';

            return [];
        }

        if ($has_it && -1 == $it_col) {
            $errors[] = 'Unable to determine IT column';

            return [];
        }

        // $out->writeln($grosscol);
        $data = [];

        $slno = 1;
        $tds_total = 0;
        for (; $i < count($innerlines); $i++) {
            $l = $innerlines[$i];
            $slnotxt = sprintf('%u,', $slno);
            //  $out->writeln($slnotxt);

            if (0 == strncmp($l, $slnotxt, strlen($slnotxt))) {
                $slno++;
                $cols = str_getcsv($l);

                $pen = strstr($cols[1], ' ', true);

                $name = strstr($cols[1], ' ');
                $name = trim(strstr($name, '-', true)); //remove any hyphen 'revised'

                $tds = '0';

                if ($has_it) {
                    $tds = $cols[$it_col];
                    $tds_total += $cols[$it_col];

                    if (intval($tds) == 0 && $tds_rows_only) {
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

                $pens[] = $pen;

                $data[] = $items;
            }
        }

        //verify tds with total
        if ($has_it) {
            for ($r = count($innerlines) - 1; $it_col !== -1 && $r >= 0; $r--) {
                $totalline = $innerlines[$r];
                if (0 == strncmp($totalline, 'Total', strlen('Total'))) {
                    $cols = str_getcsv($totalline);
                    $total_as_per_sheet = $cols[$it_col - 1]; //sl.no and name cols merged into one, so one before
                    if ($tds_total != $total_as_per_sheet) {
                        $errors[] = 'Unable to verify total tds'.$tds_total.'-'.$total_as_per_sheet;

                        return [];
                    }

                    break;
                }
            }
        }

        return  $data;
    }

    public function processpdftext($inner, $no_lattice, &$pens, &$errors, &$acquittance, &$sparkcode, $tds_rows_only, $has_it)
    {
        // $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        $innerlines = explode("\r\n", $inner);
        $nolattice_lines = explode("\r\n", $no_lattice);

        $start = 0;
        $type = $this->getpdfType($innerlines, $nolattice_lines, $start, $acquittance, $sparkcode);

        switch($type) {
            case self::SALARYBILL:
            case self::ONAMADVANCE:
                return $this->processSalaryBill($start, $innerlines, $pens, $errors, $tds_rows_only, $has_it);
            case self::FESTIVALALLOWANCE:
                return $this->processFestivalAllowance($start, $innerlines, $pens, $errors, $tds_rows_only, $has_it, 'Festival');
            case self::OVERTIME_ALLOWANCE:
                return $this->processFestivalAllowance($start, $innerlines, $pens, $errors, $tds_rows_only, $has_it, 'Overtime');
            case self::BONUS:
                return $this->processFestivalAllowance($start, $innerlines, $pens, $errors, $tds_rows_only, $has_it, 'Bonus');
        }

        return [];
    }

    public function processFestivalAllowance($start, $innerlines, &$pens, &$errors, $tds_rows_only, $has_it, $fieldGross)
    {
        $heading = '';

        $i = $start;
        $i += 2;
        $heading = $innerlines[$i];

        $cols = str_getcsv($heading);
        $grosscol = -1;
        $it_col = -1;
        for ($j = 0; $j < count($cols); $j++) {
            if (strpos($cols[$j], $fieldGross /*'Festival'*/) !== false) {
                $grosscol = $j;
            }
        }

        // dd($cols);
        if (-1 == $grosscol) {
            $errors[] = 'Unable to determine'.$fieldGross.'Allowance column';

            return [];
        }

        if ($has_it && -1 == $it_col) {
            $errors[] = 'Unable to determine IT column';

            return [];
        }

        $data = [];

        $slno = 1;
        $tds_total = 0;
        for (; $i < count($innerlines); $i++) {
            $l = $innerlines[$i];
            $slnotxt = sprintf('%u,', $slno);
            //  $out->writeln($slnotxt);

            if (0 == strncmp($l, $slnotxt, strlen($slnotxt))) {
                $slno++;
                $cols = str_getcsv($l);

                $pen = trim($cols[1]); //remove any hyphen 'revised'
                $name = trim($cols[2]); //remove any hyphen 'revised'

                $items = [
                    'slno' => $slno - 1,
                    'pen' => $pen,
                    'name' => $name,
                    'gross' => $cols[$grosscol],
                    'tds' => '0',

                ];

                $pens[] = $pen;

                $data[] = $items;
            }
        }

        return  $data;
    }
}
