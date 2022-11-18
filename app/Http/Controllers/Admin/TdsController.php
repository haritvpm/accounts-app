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
      //$out = new \Symfony\Component\Console\Output\ConsoleOutput();
      //$out->writeln($request->period);


      //$startDate = Carbon::createFromFormat('Y-m-d', trim($request->year).'-11-01');
      //$endDate = Carbon::createFromFormat('Y-m-d', trim($request->year).'-11-31');
        $taxEntries = TaxEntry::whereMonth('date', '11')->get()->pluck('id');
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln($taxEntries->count());

        $tds = Td::whereIn( 'date_id', $taxEntries )->get();
        $out->writeln($tds->count());

        $this->array_to_csv_download($tds);
        return back();
    }

    function array_to_csv_download($array, $filename = "export.csv", $delimiter = ",")
    {
   $out = new \Symfony\Component\Console\Output\ConsoleOutput();
   $out->writeln('fgdfgf');
    

        header('Content-Type: application/csv');

        header('Content-Disposition: attachment; filename="' . $filename . '";');


        // open the "output" stream
        // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
        $f = fopen('php://output', 'w');

        foreach ($array as $line) {
           // $out->writeln($line);
           // $out->writeln($line[0]);

            fputcsv($f, $line[0]);
        }
        fclose($f);

        // flush buffer
        ob_flush();

        // use exit to get rid of unexpected output afterward
        exit();

    }
}