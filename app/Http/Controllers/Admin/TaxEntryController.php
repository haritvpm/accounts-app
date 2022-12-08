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

        return view('admin.taxEntries.index', compact('taxEntries'));
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

    /*     public function massDestroy(MassDestroyTaxEntryRequest $request)
    {
    TaxEntry::whereIn('id', request('ids'))->delete();
    return response(null, Response::HTTP_NO_CONTENT);
    } */
}
