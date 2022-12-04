<?php

namespace App\Services;


class Extract
{

    const UNKNOWN = "UNKNOWN";
    const SALARYBILL= "SALARYBILL";
    const SALARYBILLMULTIPLE= "SALARYBILLMULTIPLE";

    public function __construct()
    {
        
    }

    public function getpdfType($innerlines, &$start, &$acquittance, &$sparkcode)
    {
        $start = 0;
        $type = self::UNKNOWN;
        $sparkcode = '';

        for (; $start < count($innerlines); $start++) {

          
            $l = $innerlines[$start];

            
            if (0 == strncmp($l, "Spark Code :", strlen("Spark Code :"))) {
                $arr = explode('Spark Code :', $l);
                $sparkcode = strstr($arr[1], ',', true);
                               
            }
         
            if( $type !== self::UNKNOWN && strlen($sparkcode)){
                break; //already found a type. no need to parse fully
            }

            if (0 == strncmp($l, "GOVERNMENT OF KERALA", strlen("GOVERNMENT OF KERALA"))) {

                //GET month between FOR and coma in: PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,
              
                if( FALSE !== strpos( $innerlines[$start + 3], "PAY AND ALLOWANCE IN RESPECT OF" ))
                {
                    $type = self::SALARYBILL;
                    if( FALSE !== strpos( $innerlines[$start + 3], " PERIOD " ))
                    {
                        $type = self::SALARYBILLMULTIPLE;
                    }
                        
                     //GET month between FOR and coma in: PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,
              
                    $arr = explode(' OF ', $innerlines[$start + 3]);
                    $inneracquittance = strstr($arr[1], ' FOR ', true);

                    $arr = explode(' FOR ', $innerlines[$start + 3]);
                    $innermonth = strstr($arr[1], ',', true);
                  //  break;
                    $acquittance = $inneracquittance . ' for ' . $innermonth;
                }
            }
        }
        
        

        return $type;
    }

    public function processSalaryBill($start, $innerlines,&$pens, &$errors )
    {

        $heading = '';

        $i = $start;
        $i += 4;
        $heading = $innerlines[$i];
        
        $cols = str_getcsv($heading);
        $grosscol = -1;
        $it_col = -1;
        for ($j = 0; $j < count($cols); $j++) {

            if (strpos($cols[$j], "Gross") !== false) {
                $grosscol = $j;
             
            }
            if (strcmp($cols[$j], "IT") === 0) {
                $it_col = $j;
            }

        }

       // dd($cols);
        if (-1 == $grosscol || -1 == $it_col) {
            $errors[] = "Unable to determine gross or IT column";
            return [];
        }

        // $out->writeln($grosscol);
        $data = array();
        
        $slno = 1;
        $tds_total = 0;
        for (; $i < count($innerlines); $i++) {
            $l = $innerlines[$i];
            $slnotxt = sprintf("%u,", $slno);
            //  $out->writeln($slnotxt);

            if (0 == strncmp($l, $slnotxt, strlen($slnotxt))) {
                $slno++;
                $cols = str_getcsv($l);

                $pen = strstr($cols[1], ' ', true);
                
                $name = strstr($cols[1], ' ');
                $name = trim(strstr($name, '-', true)); //remove any hyphen 'revised'

                $tds = '0';

                if( $it_col !== -1 ){
                    $tds =  $cols[$it_col];
                    $tds_total += $cols[$it_col];
                }

                $items = array(
                    'slno'=> $slno-1,
                    'pen'=> $pen ,
                    'name'=> $name,
                    'gross'=> $cols[$grosscol],
                    'tds'=> $tds,
                    
                );

                $pens[] = $pen;
             
                $data[] = $items;

            }
        }

        //verify tds with total
              
        for( $r = count( $innerlines )-1; $it_col !== -1 && $r >=0 ; $r-- ){
            $totalline = $innerlines[$r];
            if ( 0 == strncmp($totalline, "Total", strlen("Total"))){
                $cols = str_getcsv($totalline);
                $total_as_per_sheet = $cols[$it_col-1]; //sl.no and name cols merged into one, so one before
                if( $tds_total != $total_as_per_sheet ){
                    $errors[] = "Unable to verify total tds" . $tds_total . '-' . $total_as_per_sheet ;
                    return [];
                } 

                break;
            }
        }
        

        return  $data ;
    }
    

    public function processpdftext($inner, &$pens, &$errors,&$acquittance, &$sparkcode)
    {
       // $out = new \Symfony\Component\Console\Output\ConsoleOutput();
       

        $innerlines = explode("\r\n", $inner);

        $start = 0;
        $type = $this->getpdfType($innerlines, $start, $acquittance, $sparkcode);
        

        switch( $type){

            case self::SALARYBILL:
                return $this->processSalaryBill($start, $innerlines, $pens, $errors);
            
        }
            
        return [];

    }

}