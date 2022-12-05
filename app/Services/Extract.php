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
    const DA_ARREAR = 'DA_ARREAR';
    const PAY_ARREAR = 'PAY_ARREAR';

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
            if (0 == strncmp($l, 'Due and drawn statement cum D.A. ARREAR', strlen('Due and drawn statement cum D.A. ARREAR'))) {
                $type = self::DA_ARREAR;
                $acquittance = $innerlines[$i];
                $acquittance = strstr($acquittance, ',', true);
                $start = $i;
            }
            if (0 == strncmp($l, 'Due and drawn statement cum PAY', strlen('Due and drawn statement cum PAY'))) {
                $type = self::PAY_ARREAR;
                $acquittance = $innerlines[$i];
                $acquittance = strstr($acquittance, ',', true);
                $start = $i+1; //on more column than DA arrear statement to the header
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

    public function findColumnIndex($start, $innerlines, $has_it, &$errors, &$grosscol, &$it_col, $fieldGross='Gross Salary', $fieldIT='IT')
    {
        
        $grosscol = -1;
        $it_col = -1;

        for ($i = $start; $i < count($innerlines); $i++) {
            
            $l = $innerlines[$i];
            $heading = str_replace("\r",' ', $l);
            
            $cols = str_getcsv($heading);

            for ($j = 0; $j < count($cols); $j++) {
                if (strcmp($cols[$j], $fieldGross ) === 0) {
                    $grosscol = $j;
                }
                if (strcmp($cols[$j], $fieldIT) === 0) {
                    $it_col = $j;
                }
            }
            
            if (-1 !== $grosscol){
                break;
            }
        }
   

        // dd($cols);
        if (-1 == $grosscol) {
            $errors[] = 'Unable to determine column for ' . $fieldGross;

            return false;
        }

        if ($has_it && -1 == $it_col) {
            $errors[] = 'Unable to determine column for ' . $fieldIT;

            return false;
        }


        return true;
    }

    public function processSalaryBill($start, $innerlines, &$pens, &$errors, $tds_rows_only, $has_it)
    {
        $i = $start;
        $i += 5;
   
        if(! $this->findColumnIndex($start, $innerlines,  $has_it, $errors, $grosscol, $it_col  ) ){
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
                return $this->processFestivalAllowance($start, $innerlines, $pens, $errors, $tds_rows_only, $has_it, 'Festival Allowance');
            case self::OVERTIME_ALLOWANCE:
                return $this->processFestivalAllowance($start, $innerlines, $pens, $errors, $tds_rows_only, $has_it, 'Overtime Duty Allowance');
            case self::BONUS:
                return $this->processFestivalAllowance($start, $innerlines, $pens, $errors, $tds_rows_only, $has_it, 'Bonus');
            case self::DA_ARREAR:
            case self::PAY_ARREAR:
                return $this->processDaArrear($start, $innerlines, $pens, $errors, $tds_rows_only, $has_it );
            case self::SALARYBILLMULTIPLE:
                return $this->processDaArrear($start, $innerlines, $pens, $errors, $tds_rows_only, $has_it, 'Gross Salary', 'IT', 1 );
        }

        return [];
    }

    public function processFestivalAllowance($start, $innerlines, &$pens, &$errors, $tds_rows_only, $has_it, $fieldGross)
    {
        $heading = '';

        $i = $start;
        $i += 2;
   
        if(! $this->findColumnIndex($start, $innerlines,  $has_it, $errors, $grosscol, $it_col, $fieldGross ) ){
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

    ////
    public static function IsPEN($pen)
    {
        return  (strlen( $pen) >=6) && (strlen( $pen)  <= 8) &&
                ((int) filter_var($pen, FILTER_SANITIZE_NUMBER_INT) >= 100000) && //should have a number inside exlcuding chars
                FALSE === strpos($pen, ' ');

    }
    /*
    $coloffset = How many columns are merged to show 'Total' minus one. For DA, PAY it is 3. For Multiple Month Salary, it is 2

    */
    public function processDaArrear($start, $innerlines, &$pens, &$errors, $tds_rows_only, $has_it, 
                                    $fieldgross='Total', $fieldIT='Income tax to be deducted',
                                    $coloffset = 2)
    {
        $heading = '';

        $i = $start;
        $i += 4;
         
        if(! $this->findColumnIndex($start, $innerlines,  $has_it, $errors, $grosscol, $it_col, $fieldgross, $fieldIT ) ){
            return [];
        }

        $data = [];

        $slno =1;
        $totalarrear = 0;
        $totaltds = 0;

        for (; $i < count($innerlines); $i++) {
            $l = $innerlines[$i]; //"176624 Anil Kumar B , Office Superintendent",,,,,,,,,,,,,,,,,,,,,
            $cols = str_getcsv($l);
            
            //last line
            if( 0 ===  strcmp($cols[0], "Grand Total(in figures)")  ){
               
                if($cols[$grosscol-$coloffset] != $totalarrear) {
                    $errors[] = 'Grand Total and individual sum dont match';
                    return [];
                }
                if($cols[$it_col-$coloffset] != $totaltds){
                    $errors[] = 'Grand Total TDS and individual sum dont match';
                    return [];
                }
                break;
            }

            $penname = explode( ',',$cols[0]) [0];

            $pen = trim(strstr($penname, ' ', true)); //remove any hyphen 'revised'
            $name = trim(strstr($penname, ' ')); //remove any hyphen 'revised'

            if( Extract::IsPEN($pen)  ) //it is a PEN 
            {
                //find next 'total' line
                for (; $i < count($innerlines); $i++) {
                    $l = $innerlines[$i];
                    $cols = str_getcsv($l);

                    if( $cols[0] == 'Total' ){

                        $gross = $cols[$grosscol-$coloffset];// first two cols are merged
                        $tds = $cols[$it_col-$coloffset];// first two cols are merged
                        $items = [
                            'slno' => $slno++,
                            'pen' => $pen,
                            'name' => $name,
                            'gross' =>$gross ,
                            'tds' => $tds,
        
                        ];
        
                        $pens[] = $pen;
                        $data[] = $items;
                        $totalarrear += $gross;
                        $totaltds += $tds;
                        break;
                    }
                }
            }

            

        }

//        dd( $data);

        return  $data;
    }
}
