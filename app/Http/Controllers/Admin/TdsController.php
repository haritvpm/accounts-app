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
use App\Exports\ExportExcelReport;

class TdsController extends Controller
{
    public function index()
    {
       // abort_if(Gate::denies('td_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tds = Td::with(['date', 'created_by'])
        ->whereHas('date', function($q){
            $q->whereYear('created_at', '>=', Carbon::now()->subYears(2)->toDateTimeString()); //show only last 2 year data
        })
        ->whereHas('created_by', function($q)  {
            // Query the name field in status table
            $q->where('ddo', auth()->user()->ddo); // '=' is optional
        })
        ->get();

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
      //$out = new \Symfony\Component\Console\Output\ConsoleOutput();
     // $out->writeln($request->period);

      $months = array (
       
        array('4','5','6'),
        array('7','8','9'),
        array('10','11','12'),
        array('1','2','3'),
      );

      $period = (int)$request->period;
      $year = trim($request->year);

      $taxEntries = [];
      $adminEntries = [];
	  $monthnames = [];
    
      for ($i=0; $i < 3 ; $i++) { 
      
        $taxEntries[] = TaxEntry::with('dateTds', 'created_by')
        ->has('dateTds')
        ->where('created_by_id', '<>', auth()->id())
        ->whereYear('date', $year)
        ->whereMonth('date',  $months[$period][$i] )
        ->whereHas('created_by', function($q)  {
            // Query the name field in status table
            $q->where('ddo', auth()->user()->ddo); // '=' is optional
        })
        ->get();

        $adminEntries[] = TaxEntry::with('dateTds', 'created_by')
        ->has('dateTds')
        ->where('created_by_id', auth()->id())
        ->whereYear('date', $year)
        ->whereMonth('date',  $months[$period][$i] )
        ->whereHas('created_by', function($q)  {
           
            // Query the name field in status table
            $q->where('ddo', auth()->user()->ddo); // '=' is optional
        })
        ->get();
        
        $monthnames[] = Carbon::createFromDate(2019,  (int)$months[$period][$i] , 1 )->format('F');
      }
        
       

        return Excel::download(new ExportExcelReport($taxEntries, $adminEntries, $months[$period]),  
                    $year . '-'. implode( '-', $monthnames).'.xlsx');

        return back();
    }

}