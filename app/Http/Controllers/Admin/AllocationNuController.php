<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyAllocationNuRequest;
use App\Http\Requests\StoreAllocationNuRequest;
use App\Http\Requests\UpdateAllocationNuRequest;
use App\Models\AllocationNu;
use App\Models\Head;
use App\Models\Year;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllocationNuController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
       // abort_if(Gate::denies('allocation_nu_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $allocationNus = AllocationNu::with(['year', 'head', 'created_by'])->get();

        return view('admin.allocationNus.index', compact('allocationNus'));
    }

    public function create()
    {
        //abort_if(Gate::denies('allocation_nu_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $years = Year::pluck('financial_year', 'id')->prepend(trans('global.pleaseSelect'), '');

        $heads = Head::pluck('object_head', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.allocationNus.create', compact('heads', 'years'));
    }

    public function store(StoreAllocationNuRequest $request)
    {
        $allocationNu = AllocationNu::create($request->all());

        return redirect()->route('admin.allocation-nus.index');
    }

    public function edit(AllocationNu $allocationNu)
    {
        //abort_if(Gate::denies('allocation_nu_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $years = Year::pluck('financial_year', 'id')->prepend(trans('global.pleaseSelect'), '');

        $heads = Head::pluck('object_head', 'id')->prepend(trans('global.pleaseSelect'), '');

        $allocationNu->load('year', 'head', 'created_by');

        return view('admin.allocationNus.edit', compact('allocationNu', 'heads', 'years'));
    }

    public function update(UpdateAllocationNuRequest $request, AllocationNu $allocationNu)
    {
        $allocationNu->update($request->all());

        return redirect()->route('admin.allocation-nus.index');
    }

    public function show(AllocationNu $allocationNu)
    {
        //abort_if(Gate::denies('allocation_nu_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $allocationNu->load('year', 'head', 'created_by');

        return view('admin.allocationNus.show', compact('allocationNu'));
    }

    public function destroy(AllocationNu $allocationNu)
    {
        //abort_if(Gate::denies('allocation_nu_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $allocationNu->delete();

        return back();
    }

    public function massDestroy(MassDestroyAllocationNuRequest $request)
    {
        $allocationNus = AllocationNu::find(request('ids'));

        foreach ($allocationNus as $allocationNu) {
            $allocationNu->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
