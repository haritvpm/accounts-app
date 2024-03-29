<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAllocationRequest;
use App\Http\Requests\StoreAllocationRequest;
use App\Http\Requests\UpdateAllocationRequest;
use App\Models\Allocation;
use App\Models\Year;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class AllocationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('allocation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $allocations = Allocation::with(['year', 'created_by'])
        ->whereHas('created_by', function ($q) {
            // Query the name field in status table
            $q->where('ddo', auth()->user()->ddo); // '=' is optional
        })
        ->get();

        return view('admin.allocations.index', compact('allocations'));
    }

    public function create()
    {
        abort_if(Gate::denies('allocation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $years = Year::pluck('financial_year', 'id'); //->prepend(trans('global.pleaseSelect'), '');

        return view('admin.allocations.create', compact('years'));
    }

    public function store(StoreAllocationRequest $request)
    {
        $allocation = Allocation::create($request->all());

        return redirect()->route('admin.allocations.index');
    }

    public function edit(Allocation $allocation)
    {
        abort_if(Gate::denies('allocation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $years = Year::pluck('financial_year', 'id'); //->prepend(trans('global.pleaseSelect'), '');

        $allocation->load('year');

        return view('admin.allocations.edit', compact('years', 'allocation'));
    }

    public function update(UpdateAllocationRequest $request, Allocation $allocation)
    {
        $allocation->update($request->all());

        return redirect()->route('admin.allocations.index');
    }

    public function show(Allocation $allocation)
    {
        abort_if(Gate::denies('allocation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $allocation->load('year');

        return view('admin.allocations.show', compact('allocation'));
    }

    public function destroy(Allocation $allocation)
    {
        abort_if(Gate::denies('allocation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $allocation->delete();

        return back();
    }

    public function massDestroy(MassDestroyAllocationRequest $request)
    {
        Allocation::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
