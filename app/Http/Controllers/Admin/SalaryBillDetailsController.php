<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySalaryBillDetailRequest;
use App\Http\Requests\StoreSalaryBillDetailRequest;
use App\Http\Requests\UpdateSalaryBillDetailRequest;
use App\Models\SalaryBillDetail;
use App\Models\Year;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class SalaryBillDetailsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('salary_bill_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salaryBillDetails = SalaryBillDetail::with(['year', 'created_by'])->get();

        return view('admin.salaryBillDetails.index', compact('salaryBillDetails'));
    }

    public function create()
    {
        abort_if(Gate::denies('salary_bill_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $years = Year::pluck('financial_year', 'id')->prepend(trans('global.pleaseSelect'), '');
        $years = Year::latest('id')->where('active', 1)->pluck('financial_year', 'id'); //->prepend(trans('global.pleaseSelect'), '');

        return view('admin.salaryBillDetails.create', compact('years'));
    }

    public function store(StoreSalaryBillDetailRequest $request)
    {
        $salaryBillDetail = SalaryBillDetail::create($request->all());

        return redirect()->route('admin.salary-bill-details.index');
    }

    public function edit(SalaryBillDetail $salaryBillDetail)
    {
        abort_if(Gate::denies('salary_bill_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $years = Year::pluck('financial_year', 'id')->prepend(trans('global.pleaseSelect'), '');
        $years = Year::latest('id')->where('active', 1)->pluck('financial_year', 'id'); //->prepend(trans('global.pleaseSelect'), '');

        $salaryBillDetail->load('year', 'created_by');

        return view('admin.salaryBillDetails.edit', compact('years', 'salaryBillDetail'));
    }

    public function update(UpdateSalaryBillDetailRequest $request, SalaryBillDetail $salaryBillDetail)
    {
        $salaryBillDetail->update($request->all());

        return redirect()->route('admin.salary-bill-details.index');
    }

    public function show(SalaryBillDetail $salaryBillDetail)
    {
        abort_if(Gate::denies('salary_bill_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salaryBillDetail->load('year', 'created_by');

        return view('admin.salaryBillDetails.show', compact('salaryBillDetail'));
    }

    public function destroy(SalaryBillDetail $salaryBillDetail)
    {
        abort_if(Gate::denies('salary_bill_detail_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salaryBillDetail->delete();

        return back();
    }

    public function massDestroy(MassDestroySalaryBillDetailRequest $request)
    {
        SalaryBillDetail::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
