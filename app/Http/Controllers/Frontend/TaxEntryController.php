<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTaxEntryRequest;
use App\Http\Requests\StoreTaxEntryRequest;
use App\Http\Requests\UpdateTaxEntryRequest;
use App\Models\TaxEntry;
use App\Models\Td;
//use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tabula\Tabula;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class TaxEntryController extends Controller
{

    public function index()
    {
        //abort_if(Gate::denies('tax_entry_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $taxEntries = TaxEntry::with(['created_by'])->get();

        return view('frontend.taxEntries.index', compact('taxEntries'));
    }

    public function create()
    {
        //abort_if(Gate::denies('tax_entry_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.taxEntries.create');
    }

    public function store(StoreTaxEntryRequest $request)
    {
       
        $time = time();

        $fileName1 = $time . '1.' . $request->file1->extension();
        $request->file1->move(public_path('uploads'), $fileName1);
        $fileName1 = public_path('uploads') . '/' . $fileName1;

        $fileName2 = $time . '2.' . $request->file2->extension();
        $request->file2->move(public_path('uploads'), $fileName2);
        $fileName2 = public_path('uploads') . '/' . $fileName2;


        $tabula = new Tabula('/usr/bin/');



        //Tabula PHP does not return a value. It was set to output to file using 'output' param
        // So I edited vendor/initred/laravel-tabula/src/InitRed/Tabula/Tabula.php to  return $process->getOutput(); in function run()

        $result1 = $tabula->setPdf($fileName1)
            ->setOptions([
                'format' => 'csv',
                'pages' => 'all',
                'lattice' => true,
                'stream' => false,
            ])
            ->convert();

        $tabula2 = new Tabula('/usr/bin/');

        $result2 = $tabula2->setPdf($fileName2)
            ->setOptions([
                'format' => 'csv',
                'pages' => 'all',
                'lattice' => true,
                'stream' => false,
               // 'outfile' => storage_path("app/public/out4.csv"),
            ])
            ->convert();


        //handle conversion
        $pens = array();
        $errors = array();

   
        $date = Carbon::createFromFormat('d/m/Y',$request->date )->format('Y-m-d');
        $taxEntry = TaxEntry::where('date',$date)->first(); 
        
        if(!$taxEntry)
        {
            $taxEntry = TaxEntry::create($request->except(['file1', 'file2']));
        } 

        $data = $this->process($result1, $result2, $taxEntry->id, $pens, $errors);

        File::delete($fileName1, $fileName2);
        
        if( count($errors) > 0 ){
           // $taxEntry->delete();
            return redirect()->back()->withErrors($errors);
        }

       
        //$this->array_to_csv_download($data);
        if( count($data) > 0 ){
            //remove all existing items if we have similar pen

            Td::where('date_id', $taxEntry->id)->whereIn( 'pen', $pens )->delete();
            Td::insert($data);
        }

        return redirect()->route('frontend.tax-entries.index');
    }

    public function edit(TaxEntry $taxEntry)
    {
        // abort_if(Gate::denies('tax_entry_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $taxEntry->load('created_by');

        return view('frontend.taxEntries.edit', compact('taxEntry'));
    }

    public function update(UpdateTaxEntryRequest $request, TaxEntry $taxEntry)
    {
        //$out = new \Symfony\Component\Console\Output\ConsoleOutput();
       //     $out->writeln($request->all());

        $taxEntry->update($request->only(['status']));

        return redirect()->route('frontend.tax-entries.index');
    }

    public function show(TaxEntry $taxEntry)
    {
        //  abort_if(Gate::denies('tax_entry_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $taxEntry->load('created_by', 'dateTds');

        return view('frontend.taxEntries.show', compact('taxEntry'));
    }

    public function destroy(TaxEntry $taxEntry)
    {
        //  abort_if(Gate::denies('tax_entry_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //delete Tds first
        Td::where('id', $taxEntry->id)->delete();
        $taxEntry->delete();

        return back();
    }

    /*     public function massDestroy(MassDestroyTaxEntryRequest $request)
    {
    TaxEntry::whereIn('id', request('ids'))->delete();
    return response(null, Response::HTTP_NO_CONTENT);
    } */



    public function process($inner, $ded, $taxentry_id, &$pens, &$errors)
    {
        //$out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $data = array();

        $innerlines = explode("\r\n", $inner);

        $heading = '';

        $i = 0;
        $innermonth = '';

        for (; $i < count($innerlines); $i++) {

          
            $l = $innerlines[$i];
            //$out->writeln(($l));

            if (0 == strncmp($l, "GOVERNMENT OF KERALA", strlen("GOVERNMENT OF KERALA"))) {

                //GET month between FOR and coma in: PAY AND ALLOWANCE IN RESPECT OF Gazetted Officers1 FOR October 2022,,,,,,,,,,,,,,,,,,,,,,,,,,,
                $innermonth = $innerlines[$i + 3];
                $arr = explode(' FOR ', $innermonth);
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
            $errors[] = "Deduction document could not be parsed";
            return $data;
        }

        $slno = 1;

        $dedmonth = $dedlines[4];

        $arr = explode(' for ', $dedmonth);
        $dedmonth = strstr($arr[1], ',', true);


        if (0 !== strcasecmp($dedmonth, $innermonth)) {
         
            $errors[]= "Document months not the same";
            return $data;
        }

        //$data = [implode(',', ['Sl.No', 'PAN of the deductee', 'PEN of the deductee', 'Name of the deductee', 'Amount paid/credited', 'TDS', 'Date of credit'])];
       
        for ($i = 0; $i < count($dedlines); $i++) {
            $l = $dedlines[$i];
            $slnotxt = sprintf("%u,", $slno);
            //  $out->writeln($slnotxt);


            if (0 == strncmp($l, $slnotxt, strlen($slnotxt))) {
                $slno++;
                $cols = str_getcsv($l);
                $items = array(
                    'slno'=> $cols[0],
                    'pan'=>$cols[3],
                    'pen'=>$cols[1] ,
                    'name'=> $cols[2],
                    'gross'=> $pentogross[$cols[1]],
                    'tds'=> $cols[4],
                    'date_id' => $taxentry_id,
                    'created_by_id' =>auth()->id()
                );

                $pens[] = $cols[1];

                //array_push($data, implode(',', $items));
                $data[] = $items;
            }
        }


        return $data;

    }

    function array_to_csv_download($array, $filename = "export.csv", $delimiter = ",")
    {


        header('Content-Type: application/csv');

        header('Content-Disposition: attachment; filename="' . $filename . '";');


        // open the "output" stream
        // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
        $f = fopen('php://output', 'w');

        foreach ($array as $line) {
            fputcsv($f, explode(',', $line), $delimiter);
        }
        fclose($f);

        // flush buffer
        ob_flush();

        // use exit to get rid of unexpected output afterward
        exit();

    }

}