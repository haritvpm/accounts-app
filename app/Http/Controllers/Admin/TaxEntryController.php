<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTaxEntryRequest;
use App\Http\Requests\UpdateTaxEntryRequest;
use App\Models\TaxEntry;
use App\Models\Td;
//use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tabula\Tabula;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Services\Extract;

class TaxEntryController extends Controller
{
    public function index()
    {
        //abort_if(Gate::denies('tax_entry_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $taxEntries = TaxEntry::with(['created_by'])
        ->whereHas('created_by', function ($q) {
            // Query the name field in status table
            $q->where('ddo', auth()->user()->ddo); // '=' is optional
        })
        ->withSum('dateTds', 'tds')
        ->withSum('dateTds', 'gross')
        ->get();
        
        //select current month period in drop down for admin

        $period = (int)((int)Carbon::now()->month-1)  / 3 - 1 ;
        if( $period < 0) {$period = 3;}
        $period = (string)(int)$period;
       
        return view('admin.taxEntries.index', compact('taxEntries', 'period'));
    }

    public function create()
    {
        //abort_if(Gate::denies('tax_entry_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.tds.create');
    }

    public function store(StoreTdRequest $request)
    {
        //create a taxentry first with the date
        $taxEntry = TaxEntry::create([
            'date' => $request->date,
            'status' => 'approved',
        ]);

        $td = Td::create(array_merge($request->except(['date']), ['date_id' => $taxEntry->id]));

        return redirect()->route('admin.tax-entries.index');
    }

    public function edit(TaxEntry $taxEntry)
    {
        // abort_if(Gate::denies('tax_entry_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $taxEntry->load('created_by');

        return view('admin.taxEntries.edit', compact('taxEntry'));
    }

    public function update(UpdateTaxEntryRequest $request, TaxEntry $taxEntry)
    {
        //$out = new \Symfony\Component\Console\Output\ConsoleOutput();
       //     $out->writeln($request->all());

        $taxEntry->update($request->only(['status']));

        return redirect()->route('admin.tax-entries.index');
    }

    public function show(TaxEntry $taxEntry)
    {
        //  abort_if(Gate::denies('tax_entry_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $taxEntry->load('created_by', 'dateTds');
        $totaltds = number_format($taxEntry->dateTds()->sum('tds'));
        $totalgross = number_format($taxEntry->dateTds()->sum('gross'));

        return view('admin.taxEntries.show', compact('taxEntry', 'totaltds', 'totalgross'));
    }

    public function destroy(TaxEntry $taxEntry)
    {
        //  abort_if(Gate::denies('tax_entry_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //delete Tds first
        Td::where('id', $taxEntry->id)->delete();
        $taxEntry->delete();

        return back();
    }

    public function pdf2txt(Request $request)
    {
        
        $time = time();

        $fileName1 = $time.'1.'.$request->file1->extension();
        $request->file1->move(public_path('uploads'), $fileName1);
        $fileName1 = public_path('uploads').'/'.$fileName1;

        $tabula = new Tabula('/usr/bin/');

        //Tabula PHP does not return a value. It was set to output to file using 'output' param
        // So I edited vendor/initred/laravel-tabula/src/InitRed/Tabula/Tabula.php to  return $process->getOutput(); in function run()

        $result1 = $tabula->setPdf($fileName1)
            ->setOptions([
                'format' => 'csv',
                'pages' => 'all',
                'lattice' => false,
                'stream' => 1,
            ])
            ->convert();

        File::delete($fileName1);
        $innerlines = explode("\r\n", $result1);
        $data =[];
        for ( $i=0; $i < count($innerlines); $i++) {
          
            $data[] = str_replace("\r", ' ', $innerlines[$i]);
        }
        return response()->json($data);

    }

    public function pdf2csv(Request $request)
    {
        
        $time = time();

        $fileName1 = $time.'1.'.$request->file1->extension();
        $request->file1->move(public_path('uploads'), $fileName1);
        $fileName1 = public_path('uploads').'/'.$fileName1;

        $tabula = new Tabula('/usr/bin/');

        //Tabula PHP does not return a value. It was set to output to file using 'output' param
        // So I edited vendor/initred/laravel-tabula/src/InitRed/Tabula/Tabula.php to  return $process->getOutput(); in function run()

        $result1 = $tabula->setPdf($fileName1)
            ->setOptions([
                'format' => 'csv',
                'pages' => 'all',
                'lattice' => true,
                'stream' => 0,
            ])
            ->convert();

        File::delete($fileName1);
       
      
        $extract = new Extract();
        $data = $extract->pdftocsv($result1);
       // return response()->json($data);
      // dd($data);

            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );


            //$columns = array('ReviewID', 'Provider', 'Title', 'Review', 'Location', 'Created', 'Anonymous', 'Escalate', 'Rating', 'Name');

            $callback = function() use ($data)
            {
                $file = fopen('php://output', 'w');
              //  fputcsv($file, $columns);

                foreach($data as $review) {
                    fputcsv($file, $review);
                }
                fclose($file);
            };
            return \Response::stream($callback, 200, $headers);


    }

    /*     public function massDestroy(MassDestroyTaxEntryRequest $request)
    {
    TaxEntry::whereIn('id', request('ids'))->delete();
    return response(null, Response::HTTP_NO_CONTENT);
    } */
}
