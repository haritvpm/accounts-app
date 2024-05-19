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
    const UNIFORM_ALLOWANCE = 'UNIFORM_ALLOWANCE';

    const BONUS = 'BONUS';

    const ONAMADVANCE = 'ONAMADVANCE';

    const DA_ARREAR = 'DA_ARREAR';

    const PAY_ARREAR = 'PAY_ARREAR';

    const SURRENDER = 'SURRENDER';

    const MR = 'MR';
    const TA = 'Travelling Allowance';
    const PF_CLOSURE = 'PF_CLOSURE';
    const TERMINAL_SURRENDER_EL = 'TS_EL';
    const TERMINAL_SURRENDER_LEAVE = 'TS_LEAVESURRENDER';

    const SPARK_ID_PAY = 'SPARK_ID_PAY';
    const SPARK_ID_FESTIVAL_ALLOWANCE = 'SPARK_ID_FESTIVAL_ALLOWANCE';
    const SPARK_ID_LEAVESURRENDER = 'SPARK_ID_SURRENDER';
    
    const SPARK_ID_PAT = 'SPARK_ID_PAT';
   
    const DECEASED = 'DECEASED';

    public array $pens;
    public array $pen_to_name;

    public array $errors;

    public string $acquittance;

    public string $sparkcode;

    public array $innerlines;

    public $tds_rows_only;

    public $has_it;
    public $heading;
    public $excess_pay_col;

    public function __construct()
    {
        $this->pens = [];
        $this->pen_to_name = [];
        $this->errors = [];
        $this->acquittance = '';
        $this->sparkcode = '';
        $this->innerlines = [];
        $this->heading = '';
        $this->excess_pay_col = -1;
    }

    public static function getSparkCode($no_lattice, &$sparkcode)
    {
        $nolattice_lines = explode("\r\n", $no_lattice);
        for ($i = count($nolattice_lines) - 1; $i >= 0; $i--) {
            $l = $nolattice_lines[$i];

            if (false !== stripos($l, 'Spark Code :')) {
                $sparkcode = substr($l, strlen('Spark Code :') + stripos($l, 'Spark Code :'));

                $sparkcode = strtok($sparkcode, ',');

                if (false !== stripos($sparkcode, 'Page')){
                    $sparkcode = strstr( $sparkcode,"Page", true); //"Spark Code : 82378679779579887399Page: 1",
                }

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

          
            if ( false !== stripos($l, 'Spark Code :') ) {
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
            //for below, may be check next line's "Head Of Account : 2011-02-104-99-00-01-05-LH SALARIES DDO Code : 0109-67F-002",
            //to confirm this is not pay
            if (strpos($l, 'PAY AND ALLOWANCE IN RESPECT') > 0  &&
                str_contains($this->innerlines[$i + 1], '99-00-01-05')) {
                $type = self::UNIFORM_ALLOWANCE;

                //.....\rPAY AND ALLOWANCE IN RESPECT OF MLA HOSTEL FOR September 2022",

                $start = $i;
                $this->acquittance = $this->getTitle($l, 3);
            
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

            if (str_starts_with($l, 'NATURE OF CLAIM-,Terminal surrender of earned leave-Bill')) {
                $type = self::TERMINAL_SURRENDER_EL;
                $this->acquittance = 'Terminal surrender of EL';
                $start = $i + 1;
            }
            
            if (str_starts_with($l, 'NATURE OF CLAIM-,Terminal Surr of Leave')) {
                $type = self::TERMINAL_SURRENDER_LEAVE;
                $this->acquittance = 'Terminal surrender of Leave';
                $start = $i + 1;
            }
            if (str_starts_with($l, 'NATURE OF CLAIM-,Travelling Allowance - Final outer Bill')) {
                $type = self::TA;
                $this->acquittance = 'Travelling Allowance';
                $start = $i + 1;
            }

            if (str_starts_with($l, 'NATURE OF CLAIM-,TA Final Claim  (Tour)')) {
                $type = self::TA;
                $this->acquittance = 'Travelling Allowance';
                $start = $i + 1;
            }
            
            if (str_starts_with($l, 'NATURE OF CLAIM-,PF Closure / Residual PF') || 
            str_starts_with($l, 'NATURE OF CLAIM-,PF Closure/ Residual PF')) {
                $type = self::PF_CLOSURE;
                $this->acquittance = 'PF Closure';
                $start = $i + 1;
            }

            if (str_starts_with($l, 'NATURE OF CLAIM-,Pay and Allowance for Employees with SPARK ID')) {
                $type = self::SPARK_ID_PAY;
                $this->acquittance = 'Pay and Allowance for Employees with SPARK ID';
                $start = $i + 1;
            }
            if (str_starts_with($l, 'NATURE OF CLAIM-,Leave Surrender for Employees with SPARK ID')) {
                $type = self::SPARK_ID_LEAVESURRENDER;
                $this->acquittance = 'Leave Surrender for Employees with SPARK ID';
                $start = $i + 1;
            }
            

          /*   if(str_contains($l,'SPARK ID' )  ){
            dd($l);
            } */
            if (str_starts_with($l, 'NATURE OF CLAIM-,Festival Allowance for Employees with SPARK ID')) {
                $type = self::SPARK_ID_FESTIVAL_ALLOWANCE;
                $this->acquittance = 'Festival Allowance for Employees with SPARK ID';
                $start = $i + 1;
            }
            if (str_starts_with($l, 'NATURE OF CLAIM-,PAT')) {
                $type = self::SPARK_ID_PAT;
                $this->acquittance = 'PAT';
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
        // $ITColNames = ['IT', 'INCOME TAX DEDUCTION', 'Income tax to be deducted'];
        $ITColNames = ['IT', 'INCOMETAXDEDUCTION', 'Incometaxtobededucted'];

        $fieldGross = str_replace(" ", '', $fieldGross);
        $fieldIT = str_replace(" ", '', $fieldIT);

        for ($i = $start + 1; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i];
            //$heading = str_replace("\r", ' ', $l);
            $heading = str_replace("\r", '', $l);
            $heading = str_replace(" ", '', $heading);

            $this->heading = $heading;

            $cols = str_getcsv($heading);

            for ($j = 0; $j < count($cols); $j++) {
                if (strcmp($cols[$j], $fieldGross) === 0) { //this will automatically find the last matching column if there are multiple 'total' cols like in lkeave surrender
                    $grosscol = $j;
                }
                if (strcmp($cols[$j], $fieldIT) === 0) {
                    $it_col = $j;
                }
                //for salarybill, also check excesspay
                if (strcmp($cols[$j], 'ExPay') === 0) {
                    $this->excess_pay_col = $j;
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

                $remarks = '';
                
                $gross = $cols[$grosscol];
                if(-1 !== $this->excess_pay_col){
                    $excess_pay = $cols[$this->excess_pay_col];
                    if($excess_pay > 0){
                        $gross -= $excess_pay;
                        $remarks = 'ExPay:' . $excess_pay ;
                    }

                }
                $items = [
                    'slno' => $slno - 1,
                    'pen' => $pen,
                    'name' => $name,
                    'gross' => $gross,
                    'tds' => $tds,
                    'remarks' => $remarks,
                ];

                $this->pens[] = $pen;
                $this->pen_to_name[$pen] = $name;

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
// dd($type);
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
            case self::UNIFORM_ALLOWANCE:
                 return $this->processFestivalAllowance($start, 'Amount');
                
            case self::DA_ARREAR:
            case self::PAY_ARREAR:
                return $this->processDaArrear($start);
            case self::SALARYBILLMULTIPLE:
                return $this->processDaArrear($start, 'Gross Salary', 'IT', 1);
            case self::SURRENDER:
                return $this->processFestivalAllowance($start, 'TOTAL', 'INCOME TAX DEDUCTION');
            case self::MR:
            case self::TA:
            case self::PF_CLOSURE:
                 return $this->processMedical($start, 'Amount `');
            case self::TERMINAL_SURRENDER_EL:
            case self::TERMINAL_SURRENDER_LEAVE:
                 return $this->processMedical($start, 'Amount`');
            case self::SPARK_ID_PAY:
            case self::SPARK_ID_FESTIVAL_ALLOWANCE:
            case self::SPARK_ID_LEAVESURRENDER:
                return $this->processMedical($start, 'Net Amount`', 'IT');
            case self::SPARK_ID_PAT:
                return $this->processPAT($start, 'Gross Pay', 'IT');
            case self::DECEASED:
                return $this->processDeceased($start, 'Amount`');
        }
dd($type);
        return [];
    }
  
    public function replace_text_between($str, $needle_start, $needle_end, $replacement) {
        $pos = strpos($str, $needle_start);
       
        if($pos === false) return $str;
        
        $start = $pos /*+ strlen($needle_start)*/ ;
    
        $pos = strpos($str, $needle_end, $start);
        
        if($pos === false) return $str;
        
        $end = $pos + strlen($needle_start);
    
        return substr_replace($str, $replacement, $start, $end - $start);
    }

    public function get_text_between_parenthesis($text) {
      
        preg_match('#\((.*?)\)#', $text, $match);
    
        return $match[1] ?? '';
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
       // $tds_total = 0;
        for (; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i];
            $slnotxt = sprintf('%u,', $slno);
            //  $out->writeln($slnotxt);

            if (str_starts_with($l, $slnotxt)) {
                $slno++;
                $cols = str_getcsv($l);

                $pen = trim($cols[1]); //remove any hyphen 'revised'
                $name = trim($cols[2]); //remove any hyphen 'revised'
                $pen = trim($this->replace_text_between($pen, '(', ')', '')); //821472( 683/2017)
                $tds = '0';

                if ($this->has_it) {
                    $tds = $cols[$it_col];
                    // $tds = str_replace("\r", '', $tds);

                   // $tds_total += $cols[$it_col];

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
                $this->pen_to_name[$pen] = $name;


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
                        $this->pen_to_name[$pen] = $name;

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
       // $tds_total = 0;
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
                    $tds = str_replace("\r", '', $tds); //IT amount can be in two lines like 0 on next line.

                 //   $tds_total += $cols[$it_col];

                    if (intval($tds) == 0 && $this->tds_rows_only) {
                        continue;
                    }
                }

                $gross = $cols[$grosscol];
                $gross = str_replace("\r", '', $gross); // amount can be in two lines
                $gross = str_replace(" ", '', $gross); // amount can be in two lines


                $items = [
                    'slno' => $slno - 1,
                    'pen' => $pen,
                    'name' => $name,
                    'gross' => $gross,
                    'tds' => $tds,

                ];

                $this->pens[] = $pen;
                $this->pen_to_name[$pen] = $name;


                $data[] = $items;
            }
        }
        // dd($data);
        return  $data;
    }
    public function processPAT($start, $fieldGross, $fieldIT = 'IT')
    {
        $i = $start;
        $i += 2;

        if (! $this->findColumnIndex($start, $grosscol, $it_col, $fieldGross, $fieldIT)) {
            return [];
        }

        $data = [];

        $slno = 1;
       // $tds_total = 0;
        for (; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i];
            $slnotxt = sprintf('%u,', $slno);
            //  $out->writeln($slnotxt);

            if (str_starts_with($l, $slnotxt)) {
                $slno++;
                $cols = str_getcsv($l);

                
                $name = trim($cols[1]);
                $name = trim($this->replace_text_between($name, '(', ')', '')); //821472( 683/2017)
                $name = str_replace("\r", ' ', $name); //Name has pen within parenthesis
                $pen = trim($cols[1]);
                $pen = trim($this->get_text_between_parenthesis($pen));
                $pen = str_replace("\r", '', $pen);

                $tds = '0';

                if ($this->has_it) {
                    $tds = $cols[$it_col];
                    $tds = str_replace("\r", '', $tds); //IT amount can be in two lines like 0 on next line.

                 //   $tds_total += $cols[$it_col];

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
                $this->pen_to_name[$pen] = $name;


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
       // $tds_total = 0;

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
               // $tds_total += $cols[$it_col];
                $tds = str_replace("\r", '', $tds); //IT amount can be in two lines like 0 on next line.

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
            $this->pen_to_name[$account] = $name;


            $data[] = $items;
        }
        //  dd($data);
        return  $data;
    }

    //these functions are just for csv extraction from pdf. 
    public function pdftocsv($inner)
    {
        // $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        $this->innerlines = explode("\r\n", $inner);
        
        $start = 0;
        $type = $this->getpdfType($start);

        switch($type) {
            case self::SALARYBILL:
                return $this->extractSalaryBill($start);
        }

        return [];
    }    

    public function extractSalaryBill($start)
    {
       
        $i = $start;
        $i += 5;
        
        $grosscol=0;
        $it_col=0;
        $this->findColumnIndex($start, $grosscol, $it_col);
        
        $data = [];

        $heading_cols = str_getcsv($this->heading);
        $items = [
            'slno' => 'Sl.',
            'pen' => 'Pen',
            'name' => 'Name',
          
        ];

        for ($j = 2 ; $j < count($heading_cols); $j++){
            $items[$j] = $heading_cols[$j];
        }
        $data[] = $items;


        $slno = 1;
       
        for (; $i < count($this->innerlines); $i++) {
            $l = $this->innerlines[$i];
            $slnotxt = sprintf('%u,', $slno);
            //  $out->writeln($slnotxt);

            if (str_starts_with($l, $slnotxt)) {
                $slno++;
                $cols = str_getcsv($l);
               // dd($cols);
                //"821472 ( 683/2017 )NAVEENJAMES NORONA -Revised"
                $penname = str_replace("\r", ' ', $cols[1]);

                $pen = strstr($penname, ' ', true);

                $name = Str::of($penname)->after(' ')->before('-')->after(')')->trim();

               

                $items = [
                    'slno' => $slno - 1,
                    'pen' => $pen,
                    'name' => $name,
                  
                ];
                for ($j =2 ; $j < count($cols); $j++){
                    $items[$j] = $cols[$j];
                }
                
             

                $data[] = $items;
            }
        }

       

        return  $data;
    }
}
