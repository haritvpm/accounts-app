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
        $taxEntry = TaxEntry::create($request->all());

        if ($request->input('innerfile', false)) {

        }

        if ($request->input('deductionfile', false)) {

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

}