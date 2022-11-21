/* maatexcel 2.1.0. old excel format. works ok

    function downloadExcel( $data,$adminEntriesArray, $type, $months, $year)
	{
		$monthnames = [];
        foreach( $months as $month ){
            $monthname = Carbon::createFromDate(2019,  (int)$month, 1 )->format('F');
            
            $monthnames[] = $monthname;
        }
        
      
      //
		return Excel::create( $year . '-'. implode( '-', $monthnames ) , function($excel) 
                use ($data, $adminEntriesArray, $months, $monthnames) {
			
            for ($index=0, $month = (int)$months[0] ; $month <=  (int)$months[2] ; $month++, $index++) {

                $taxentries = $data[$index];
                $adminEntries = $adminEntriesArray[$index];
                
                $excel->sheet($monthnames[$index], function($sheet) use ($taxentries, $adminEntries)
                {                      
                    $sheet->appendRow( array(
                        'Sl.No', 'PAN of the deductee', 'PEN of the deductee', 
                        'Name of the deductee', 'Amount paid/credited', 'TDS', 'Date of credit'
                    ));

                    $slno = 1;
                    $tdstotal = 0;
                    foreach ($taxentries as $taxentry) {

                        $tds = $taxentry->dateTds()->get();
                        
                        foreach ($tds as $cols){
                                                      
                            $items = [];
                            array_push($items, $slno++);
                            array_push($items, $cols->pan);
                            array_push($items, $cols->pen);
                            array_push($items, $cols->name);
                            array_push($items, $cols->gross);
                            array_push($items, $cols->tds);
                            array_push($items, $taxentry->date );
                            $sheet->appendRow( $items );
                            $tdstotal += $cols->tds;
                        }
                                              
                    }

                    if( $slno > 1 ) { //has entries
                        $sheet->appendRow( [ '', '', '','', 'Total', $tdstotal, ''] );
                        $slno++;
                    }


                    $sheet->appendRow( ['26Q'] );
                    $adminstartrow = ++$slno;

                    $sheet->mergeCells( 'A' . $slno . ':G' . $slno); 
                    $style = array(
                        'alignment' => array(
                            'horizontal' =>'center',
                        )
                    );
                    
                    $sheet->getStyle('A' . $slno . ':G' . $slno)->applyFromArray($style);
                        
                    $slno = 1;
                    $tdstotal = 0;

                    foreach ($adminEntries as $taxentry) {

                        $tds = $taxentry->dateTds()->get();
                        
                        foreach ($tds as $cols){
                                                      
                            $items = [];
                            array_push($items, $slno++);
                            array_push($items, $cols->pan);
                            array_push($items, $cols->pen);
                            array_push($items, $cols->name);
                            array_push($items, $cols->gross);
                            array_push($items, $cols->tds);
                            array_push($items, $taxentry->date );
                            $sheet->appendRow( $items );
                            $tdstotal += $cols->tds;
                        }
                                              
                    }

                    if( $slno > 1 ){
                        $sheet->appendRow( [ '', '', '','', 'Total', $tdstotal, ''] );
                    } else {
                        //remove '26Q
                        $sheet->removeRow($adminstartrow);
                    }

                    $sheet->setAutoSize(true);

                });
                    
                
            }

		})->download($type);
   
	}
*/