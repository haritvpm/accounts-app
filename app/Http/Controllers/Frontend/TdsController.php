<?php

namespace App\Http\Controllers\Frontend;

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

        $tds = Td::with(['date', 'created_by'])
        ->orderBy('id', 'DESC')
        ->get();

        return view('frontend.tds.index', compact('tds'));
    }

    public function create()
    {
        //abort_if(Gate::denies('td_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dates = TaxEntry::pluck('date', 'id')/* ->prepend(trans('global.pleaseSelect') , '')*/;

        return view('frontend.tds.create', compact('dates'));
    }

    public function store(StoreTdRequest $request)
    {
        $td = Td::create($request->all());

        return redirect()->route('frontend.tds.index');
    }

    public function edit(Td $td)
    {
        //abort_if(Gate::denies('td_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dates = TaxEntry::pluck('date', 'id')/* ->prepend(trans('global.pleaseSelect') , '')*/;

        $td->load('date', 'created_by');

        return view('frontend.tds.edit', compact('dates', 'td'));
    }

    public function update(UpdateTdRequest $request, Td $td)
    {
        $td->update($request->all());

        return redirect()->route('frontend.tds.index');
    }

    public function show(Td $td)
    {
        //   abort_if(Gate::denies('td_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $td->load('date', 'created_by');

        return view('frontend.tds.show', compact('td'));
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
}
