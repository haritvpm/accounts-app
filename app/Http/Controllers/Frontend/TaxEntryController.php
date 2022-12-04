<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTaxEntryRequest;
use App\Http\Requests\StoreTaxEntryRequest;
use App\Http\Requests\UpdateTaxEntryRequest;
use App\Models\Employee;
use App\Models\TaxEntry;
use App\Models\Td;
//use Gate;
use App\Services\Extract;
use App\Services\Extract311;
use App\Tabula\Tabula;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class TaxEntryController extends Controller
{
    public function index()
    {
        //abort_if(Gate::denies('tax_entry_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $taxEntries = TaxEntry::with(['created_by'])
        ->withSum('dateTds', 'tds')
        ->withSum('dateTds', 'gross')
        ->get();

        return view('frontend.taxEntries.index', compact('taxEntries'));
    }

    public function create_311()
    {
        //abort_if(Gate::denies('tax_entry_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.taxEntries.create311');
    }

    public function create()
    {
        //abort_if(Gate::denies('tax_entry_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.taxEntries.create');
    }

    public function store_311(StoreTaxEntryRequest $request)
    {
        $time = time();

        $fileName1 = $time.'1.'.$request->file1->extension();
        $request->file1->move(public_path('uploads'), $fileName1);
        $fileName1 = public_path('uploads').'/'.$fileName1;

        $fileName2 = $time.'2.'.$request->file2->extension();
        $request->file2->move(public_path('uploads'), $fileName2);
        $fileName2 = public_path('uploads').'/'.$fileName2;

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
        $pens = [];
        $errors = [];

        $date = Carbon::createFromFormat(config('panel.date_format'), $request->date)->format('Y-m-d');

        $month = Carbon::createFromFormat(config('panel.date_format'), $request->date)->format('F');

        $extract = new Extract311();
        $acquittance = '';
        $sparkcode = '';
        $data = $extract->processpdftext($result1, $result2, $pens, $errors, $acquittance, $month, $sparkcode);

        File::delete($fileName1, $fileName2);

        if (count($errors) > 0) {
            // return redirect()->back()->withErrors($errors);
            return response()->json(['error' => $errors[0]]);
        }

        $taxEntry = TaxEntry::where('sparkcode', $sparkcode)->first();
        if (! $taxEntry) {
            $taxEntry = TaxEntry::create(
                $request->except(['file1', 'file2']) + ['acquittance' => $acquittance, 'sparkcode' => $sparkcode]
            );
        } else {
            return response()->json(['error' => 'Spark code already exists']);
        }

        //$this->array_to_csv_download($data);
        if (count($data) > 0) {
            foreach ($data as $tds) {
                // $tds['date_id'] = $taxEntry->id;
            }

            //remove all existing items if we have similar pen
            Td::where('date_id', $taxEntry->id)->whereIn('pen', $pens)->delete();
            // Td::insert($data);
            $taxEntry->dateTds()->createMany($data);
            /* $taxEntry->update( [
                'acquittance' => $acquittance,
            ] ); */
        }

        return response()->json(['success' => 'You have successfully upload file.']);
        // return redirect()->route('frontend.tax-entries.index');
    }

    public function store(StoreTaxEntryRequest $request)
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
                'stream' => false,
            ])
            ->convert();

        $tabula2 = new Tabula('/usr/bin/');
        $no_lattice = $tabula2->setPdf($fileName1) //comvert without lattice mode which only takes borders, and ignores sparkcode in some pdf like festival allowance
        ->setOptions([
            'format' => 'csv',
            'pages' => 'all', //in festival allowance first page spark code and page number (1) are concatenated. it is ok in other pages. so we cant take just first page
            'stream' => true,
        ])
        ->convert();

        //handle conversion
        $pens = [];
        $errors = [];

        $date = Carbon::createFromFormat(config('panel.date_format'), $request->date)->format('Y-m-d');

        $month = Carbon::createFromFormat(config('panel.date_format'), $request->date)->format('F');

        $extract = new Extract();
        $acquittance = '';
        $sparkcode = '';
        $data = $extract->processpdftext($result1, $no_lattice,
            $pens, $errors, $acquittance, $sparkcode,
            $request->tds_rows_only, $request->has_it);

        File::delete($fileName1);

        //extract PAN
        if (count($errors) > 0) {
            return response()->json(['error' => $errors[0]]);
        }

        $empwithpen = Employee::wherein('pen', $pens)->pluck('pen');
        $penwithnoemp = array_diff($pens, $empwithpen->toArray());

        if (count($penwithnoemp)) {
            return response()->json(['error' => 'No Employee found for : '.implode(', ', $penwithnoemp)]);
        }

        $pen_to_pan = Employee::wherein('pen', $pens)->pluck('pan', 'pen');

        $data = collect($data)->transform(function ($item) use ($pen_to_pan) {
            $item['pan'] = $pen_to_pan[$item['pen']];
            $item['created_by_id'] = auth()->id();

            return $item;
        });

        if ($sparkcode == '') {
            return response()->json(['error' => 'Unable to find Spark code']);
        }

        $taxEntry = TaxEntry::where('sparkcode', $sparkcode)->first();
        if (! $taxEntry) {
            $taxEntry = TaxEntry::create(
                $request->except(['file1', 'tds_rows_only', 'has_it']) + ['acquittance' => $acquittance, 'sparkcode' => $sparkcode]
            );
        } else {
            return response()->json(['error' => 'Spark code already exists']);
        }

        if (count($data) > 0) {
            //foreach ($data as $tds) {
            // $tds['date_id'] = $taxEntry->id;
            //}

            //remove all existing items if we have similar pen. not needed since we check spark code
            Td::where('date_id', $taxEntry->id)->whereIn('pen', $pens)->delete();
            // Td::insert($data);
            $taxEntry->dateTds()->createMany($data);
            /* $taxEntry->update( [
                'acquittance' => $acquittance,
            ] ); */
        }

        return response()->json(['success' => 'You have successfully upload file.']);
        // return redirect()->route('frontend.tax-entries.index');
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
        $total = number_format($taxEntry->dateTds()->sum('tds'));

        return view('frontend.taxEntries.show', compact('taxEntry', 'total'));
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
}
