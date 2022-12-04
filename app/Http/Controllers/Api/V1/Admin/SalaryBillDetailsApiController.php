<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalaryBillDetailRequest;
use App\Http\Requests\UpdateSalaryBillDetailRequest;
use App\Http\Resources\Admin\SalaryBillDetailResource;
use App\Models\SalaryBillDetail;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class SalaryBillDetailsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('salary_bill_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SalaryBillDetailResource(SalaryBillDetail::with(['year', 'created_by'])->get());
    }

    public function store(StoreSalaryBillDetailRequest $request)
    {
        $salaryBillDetail = SalaryBillDetail::create($request->all());

        return (new SalaryBillDetailResource($salaryBillDetail))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SalaryBillDetail $salaryBillDetail)
    {
        abort_if(Gate::denies('salary_bill_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SalaryBillDetailResource($salaryBillDetail->load(['year', 'created_by']));
    }

    public function update(UpdateSalaryBillDetailRequest $request, SalaryBillDetail $salaryBillDetail)
    {
        $salaryBillDetail->update($request->all());

        return (new SalaryBillDetailResource($salaryBillDetail))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(SalaryBillDetail $salaryBillDetail)
    {
        abort_if(Gate::denies('salary_bill_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salaryBillDetail->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
