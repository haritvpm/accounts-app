<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTdRequest;
use App\Http\Requests\StoreTdRequest;
use App\Http\Requests\UpdateTdRequest;
use App\Models\TaxEntry;
use App\Models\Td;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Excel;
use Carbon\Carbon;

class TdsController extends Controller
{
    public function index()
    {
       // abort_if(Gate::denies('td_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tds = Td::with(['date', 'created_by'])->get();

        return view('admin.tds.index', compact('tds'));
    }

    public function create()
    {
        //abort_if(Gate::denies('td_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tds.create');
    }

    public function store(StoreTdRequest $request)
    {
        //create a taxentry first with the date
        $taxEntry = TaxEntry::create( [
            'date' => $request->date,
            'status' => 'approved'
        ] );

        $td = Td::create(  array_merge($request->except(['date']), [ 'date_id' => $taxEntry->id ]  ));

        return redirect()->route('admin.tds.index');
    }

    public function edit(Td $td)
    {
        //abort_if(Gate::denies('td_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //$dates = TaxEntry::pluck('date', 'id')->prepend(trans('global.pleaseSelect'), '');
               
        $td->load('date', 'created_by');
      
        return view('admin.tds.edit', compact('td'));
    }

    public function update(UpdateTdRequest $request, Td $td)
    {
        $td->update($request->except(['date']));
        //update TaxEntry date
        $taxEntry = TaxEntry::find( $td->date_id);
        $taxEntry->update( [ 'date' => $request->date ] );

       
        return redirect()->route('admin.tds.index');
    }

    public function show(Td $td)
    {
     //   abort_if(Gate::denies('td_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $td->load('date', 'created_by');

        return view('admin.tds.show', compact('td'));
    }

    public function destroy(Td $td)
    {
      //  abort_if(Gate::denies('td_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $td->delete();

        return back();
    }

   /*  public function massDestroy(MassDestroyTdRequest $request)
    {
        Td::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    } */

    public function download(Request $request)
    {
      //  abort_if(Gate::denies('td_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
      $out = new \Symfony\Component\Console\Output\ConsoleOutput();
      $out->writeln($request->period);

      $months = array (
       
        array('4','5','6'),
        array('7','8','9'),
        array('10','11','12'),
        array('1','2','3'),
      );

      $period = (int)$request->period;
      $year = trim($request->year);

      $taxEntries = [];
    
      $taxEntries[] = TaxEntry::with('dateTds')
        ->whereNotNull('created_by_id')
        ->whereYear('date', $year)
        ->whereMonth('date',  $months[$period][0] )->get();

      $taxEntries[] = TaxEntry::with('dateTds')
        ->whereNotNull('created_by_id')
        ->whereYear('date', $year)
        ->whereMonth('date', $months[$period][1] )->get();

      $taxEntries[] = TaxEntry::with('dateTds')
        ->whereNotNull('created_by_id')
        ->whereYear('date', $year)
        ->whereMonth('date', $months[$period][2] )->get();


        //ADMIN entries

      $adminEntries = [];
    
      $adminEntries[] = TaxEntry::with('dateTds')
        ->whereNull('created_by_id')
        ->whereYear('date', $year)
        ->whereMonth('date',  $months[$period][0] )->get();

      $adminEntries[] = TaxEntry::with('dateTds')
        ->whereNull('created_by_id')
        ->whereYear('date', $year)
        ->whereMonth('date', $months[$period][1] )->get();

      $adminEntries[] = TaxEntry::with('dateTds')
        ->whereNull('created_by_id')
        ->whereYear('date', $year)
        ->whereMonth('date', $months[$period][2] )->get();

        
        //$this->array_to_csv_download($taxEntries);
        $this->downloadExcel($taxEntries, $adminEntries, 'xls',  $months[$period], $year);

        return back();
    }

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

        
/*
        $data = array(
            array('data1', 'data2'),
            array('data3', 'data4')
        );
        
        Excel::create('Filename', function($excel) use($data) {
        
            $excel->sheet('Sheetname', function($sheet) use($data) {
        
                $sheet->fromArray($data);
        
            });
        
        })->export('xls');
  */      

	}

    function array_to_csv_download($array, $filename = "export.csv", $delimiter = ",")
    {
 
        header('Content-Type: application/csv');

        header('Content-Disposition: attachment; filename="' . $filename . '";');


        // open the "output" stream
        // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
        $f = fopen('php://output', 'w');
        
        foreach ($array as $taxentry) {

            $tds = $taxentry->dateTds()->get();
            foreach ($tds as $line) {
            
                fputcsv($f, array_merge($line->toArray(), [ $taxentry->date ]));
            }
        }
        fclose($f);

        // flush buffer
        ob_flush();

        // use exit to get rid of unexpected output afterward
        exit();

    }
}