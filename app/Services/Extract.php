<?php

namespace App\Services;


class Extract
{

    
    public function __construct()
    {
        
    }

    public function processpdftext($inner, $ded, &$pens, &$errors,&$acquittance, $month)
    {
        //$out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $data = array();

        $innerlines = explode("\r\n", $inner);

        $heading = '';

        $i = 0;
        $innermonth = '';
        $inneracquittance = '';


        for (; $i < count($innerlines); $i++) {

          
            $l = $innerlines[$i];
            //$out->writeln(($l));

            if (0 == strncmp($l, "GOVERNMENT OF KERALA", strlen("GOVERNMENT OF KERALA"))) {

                //GET month between FOR and coma in: PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,
              
                $arr = explode(' OF ', $innerlines[$i + 3]);
                $inneracquittance = strstr($arr[1], ' FOR ', true);

                $arr = explode(' FOR ', $innerlines[$i + 3]);
                $innermonth = strstr($arr[1], ',', true);

                //heading is 5 lines after this
                $i += 5;
                $heading = $innerlines[$i];
                //$out->writeln($heading);
                break;
            }
        }

        $cols = str_getcsv($heading);
        $grosscol = -1;
        for ($j = 0; $j < count($cols); $j++) {

            if (strpos($cols[$j], "Gross") !== false) {
                $grosscol = $j;
                break;
            }
        }

        if (-1 == $grosscol) {
            $errors[] = "InnerBill could not be parsed";
            return $data;
        }

        // $out->writeln($grosscol);

        $pentogross = array();
        $slno = 1;
        for (; $i < count($innerlines); $i++) {
            $l = $innerlines[$i];
            $slnotxt = sprintf("%u,", $slno);
            //  $out->writeln($slnotxt);

            if (0 == strncmp($l, $slnotxt, strlen($slnotxt))) {
                $slno++;
                $cols = str_getcsv($l);
                $pen = strstr($cols[1], ' ', true);
                $pentogross[$pen] = $cols[$grosscol];
            }
        }

        //
        //DEDUCTION
        //


        $dedlines = explode("\r\n", $ded);

        if (count($dedlines) < 8 || 0 != strncmp($dedlines[3], "INCOME TAX(311)", strlen("INCOME TAX(311)"))) {
            $errors[] = "INCOME TAX(311) document could not be parsed";
            return $data;
        }

        $slno = 1;

        $dedacquittance = $dedlines[4];
        $arr = explode(' Estt pay ', $dedacquittance);
        $dedacquittance = strstr($arr[1], ' for ', true);

        $dedmonth = $dedlines[4];
        $arr = explode(' for ', $dedmonth);
        $dedmonth = strstr($arr[1], ',', true);


        if (0 !== strcasecmp($inneracquittance, $dedacquittance)) {
         
            $errors[]= "Document acquittance groups not the same:\n" . $inneracquittance . ' <> ' . $dedacquittance;
            return $data;
        }

        if (0 !== strcasecmp($dedmonth, $innermonth)) {
         
            $errors[]= "Document months not the same:\n" . $innermonth . ' <> ' . $dedmonth;
            return $data;
        }

        $acquittance = $inneracquittance . ' for ' . $innermonth;

        /* this is wrong at the moment. check if deduction is always one month after
        if (0 !== strcasecmp($month, $innermonth) || (0 !== strcasecmp($month, $dedmonth) ) {
         
            $errors[]= "Date of dedudction and Document month not the same";
            return $data;
        }
        */
      

        //$data = [implode(',', ['Sl.No', 'PAN of the deductee', 'PEN of the deductee', 'Name of the deductee', 'Amount paid/credited', 'TDS', 'Date of credit'])];
       
        for ($i = 0; $i < count($dedlines); $i++) {
            $l = $dedlines[$i];
            $slnotxt = sprintf("%u,", $slno);
            //  $out->writeln($slnotxt);


            if (0 == strncmp($l, $slnotxt, strlen($slnotxt))) {
                $slno++;
                $cols = str_getcsv($l);

                $pan = str_replace(' ', '', $cols[3]); //some people have spaces in their PAN in pdf TDS.

                //if pen has spaces, ignore it as we use it to cross match pdfs from spark. it is the same in both pdf

                $items = array(
                    'slno'=> $cols[0],
                    'pan'=>$pan,
                    'pen'=>$cols[1] ,
                    'name'=> $cols[2],
                    'gross'=> $pentogross[$cols[1]],
                    'tds'=> $cols[4],
                  //  'date_id' => $taxentry_id,
                    'created_by_id' =>auth()->id()
                );

                $pens[] = $cols[1];

                //array_push($data, implode(',', $items));
                $data[] = $items;
            }
        }


        return $data;

    }

}