<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAllocationRequest;
use App\Http\Requests\UpdateAllocationRequest;
use App\Http\Resources\Admin\AllocationResource;
use App\Models\Allocation;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class AllocationApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('allocation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AllocationResource(Allocation::with(['year'])->get());
    }

    public function store(StoreAllocationRequest $request)
    {
        $allocation = Allocation::create($request->all());

        return (new AllocationResource($allocation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Allocation $allocation)
    {
        abort_if(Gate::denies('allocation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AllocationResource($allocation->load(['year']));
    }

    public function update(UpdateAllocationRequest $request, Allocation $allocation)
    {
        $allocation->update($request->all());

        return (new AllocationResource($allocation))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Allocation $allocation)
    {
        abort_if(Gate::denies('allocation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $allocation->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
