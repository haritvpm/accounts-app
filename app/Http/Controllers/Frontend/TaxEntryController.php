<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTaxEntryRequest;
use App\Http\Requests\StoreTaxEntryRequest;
use App\Http\Requests\UpdateTaxEntryRequest;
use App\Models\TaxEntry;
//use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tabula\Tabula;
use Illuminate\Support\Facades\File;

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
        $taxEntry = TaxEntry::create($request->except(['file1', 'file2']));

        if ($request->input('file1', false)) {

        }

        if ($request->input('file2', false)) {

        }


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
            ])
            ->convert();


        /* TEST output
        $tabula3 = new Tabula('/usr/bin/');
        $tabula3->setPdf($fileName2)
        ->setOptions([
        'format' => 'csv',
        'pages' => 'all',
        'lattice' => true,
        'stream' => false,
        'outfile' => storage_path("app/public/out4.csv"),
        ])
        ->convert();*/

        //handle conversion
        $data = $this->process($result1, $result2);

        File::delete($fileName1, $fileName2);
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        //$out->writeln($result2);
        //$this->array_to_csv_download($data);

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
        $taxEntry->update($request->all());

        if ($request->input('innerfile', false)) {

        } elseif ($taxEntry->innerfile) {

        }

        if ($request->input('deductionfile', false)) {

        } elseif ($taxEntry->deductionfile) {

        }

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

        $taxEntry->delete();

        return back();
    }

    /*     public function massDestroy(MassDestroyTaxEntryRequest $request)
    {
    TaxEntry::whereIn('id', request('ids'))->delete();
    return response(null, Response::HTTP_NO_CONTENT);
    } */



    public function process($inner, $ded)
    {
        //$out = new \Symfony\Component\Console\Output\ConsoleOutput();

        $innerlines = explode("\r\n", $inner);

        $heading = '';

        $i = 0;
        $innermonth = '';

        for (; $i < count($innerlines); $i++) {

            //$data[] = str_getcsv($line);
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
            return ["InnerBill could not be parsed"];
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
            return ["Deduction document could not be parsed"];
        }

        $slno = 1;

        $dedmonth = $dedlines[4];

        $arr = explode(' for ', $dedmonth);
        $dedmonth = strstr($arr[1], ',', true);


        if (0 !== strcasecmp($dedmonth, $innermonth)) {
            return ["Document months not the same"];
        }

        $data = [implode(',', ['Sl.No', 'PAN of the deductee', 'PEN of the deductee', 'Name of the deductee', 'Amount paid/credited', 'TDS', 'Date of credit'])];
        for ($i = 0; $i < count($dedlines); $i++) {
            $l = $dedlines[$i];
            $slnotxt = sprintf("%u,", $slno);
            //  $out->writeln($slnotxt);


            if (0 == strncmp($l, $slnotxt, strlen($slnotxt))) {
                $slno++;
                $cols = str_getcsv($l);
                $items = [];
                array_push($items, $cols[0]);
                array_push($items, $cols[3]); //PAN
                array_push($items, $cols[1]); //PEN
                array_push($items, $cols[2]);
                array_push($items, $pentogross[$cols[1]]);
                array_push($items, $cols[4]);
                array_push($items, 'dsfsdfds');

                array_push($data, implode(',', $items));
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