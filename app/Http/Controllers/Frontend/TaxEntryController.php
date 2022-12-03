<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTaxEntryRequest;
use App\Http\Requests\StoreTaxEntryRequest;
use App\Http\Requests\UpdateTaxEntryRequest;
use App\Models\TaxEntry;
use App\Models\Td;
use App\Models\Employee;
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

        $taxEntries = TaxEntry::with(['created_by'])->withSum('dateTds', 'tds')->get();
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
        //handle conversion
        $pens = array();
        $errors = array();
   
        $date = Carbon::createFromFormat(config('panel.date_format'),$request->date )->format('Y-m-d');
        
       
        $month = Carbon::createFromFormat(config('panel.date_format'),$request->date )->format('F');
  
        $extract = new Extract();
        $acquittance = '';
        $sparkcode ='';
        $data = $extract->processpdftext($result1, $pens, $errors,$acquittance, $sparkcode);
        File::delete($fileName1);
        
        //extract PAN
        if( count($errors) > 0 ){
           
            return response()->json(['error'=> $errors[0] ]);
        }

        
        $empwithpen = Employee::wherein( 'pen', $pens )->pluck('pen');
        $penwithnoemp = array_diff($pens, $empwithpen->toArray());
       

        if( count($penwithnoemp) ){
            return response()->json(['error'=> 'No Employee found for : ' . implode(', ', $penwithnoemp) ]);
        }


        $pen_to_pan = Employee::wherein( 'pen', $pens )->pluck( 'pan','pen');
       
        $data = collect($data)->transform(function($item) use ($pen_to_pan) {
           
            $item['pan'] = $pen_to_pan[$item['pen']];
            $item['created_by_id'] = auth()->id();
            return $item;
        });


        $taxEntry = TaxEntry::where('date',$date)->where('sparkcode', $sparkcode )->first(); 
        if(!$taxEntry)
        {
            $taxEntry = TaxEntry::create(
                $request->except(['file1', 'file2']) + ['acquittance' => $acquittance, 'sparkcode' => $sparkcode] 
            );
        } 
       
        if( count($data) > 0 ){
           
           //foreach ($data as $tds) {
              // $tds['date_id'] = $taxEntry->id;
           //}

            //remove all existing items if we have similar pen
            Td::where('date_id', $taxEntry->id)->whereIn( 'pen', $pens )->delete();
           // Td::insert($data);
            $taxEntry->dateTds()->createMany($data);
            /* $taxEntry->update( [ 
                'acquittance' => $acquittance,
            ] ); */
        }
       
        return response()->json(['success'=>'You have successfully upload file.']);
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
        $total =  number_format($taxEntry->dateTds()->sum('tds'));
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