<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySparkBillDataRequest;
use App\Http\Requests\StoreSparkBillDataRequest;
use App\Http\Requests\UpdateSparkBillDataRequest;
use App\Models\Employee;
use App\Models\SparkBill;
use App\Models\SparkBillData;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SparkBillDataController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('spark_bill_data_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sparkBillDatas = SparkBillData::with(['sparkbill', 'employee', 'created_by'])->get();

        return view('frontend.sparkBillDatas.index', compact('sparkBillDatas'));
    }

    public function create()
    {
        abort_if(Gate::denies('spark_bill_data_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sparkbills = SparkBill::pluck('sparkcode', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employees = Employee::pluck('pen', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.sparkBillDatas.create', compact('employees', 'sparkbills'));
    }

    public function store(StoreSparkBillDataRequest $request)
    {
        $sparkBillData = SparkBillData::create($request->all());

        return redirect()->route('frontend.spark-bill-datas.index');
    }

    public function edit(SparkBillData $sparkBillData)
    {
        abort_if(Gate::denies('spark_bill_data_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sparkbills = SparkBill::pluck('sparkcode', 'id')->prepend(trans('global.pleaseSelect'), '');

        $employees = Employee::pluck('pen', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sparkBillData->load('sparkbill', 'employee', 'created_by');

        return view('frontend.sparkBillDatas.edit', compact('employees', 'sparkBillData', 'sparkbills'));
    }

    public function update(UpdateSparkBillDataRequest $request, SparkBillData $sparkBillData)
    {
        $sparkBillData->update($request->all());

        return redirect()->route('frontend.spark-bill-datas.index');
    }

    public function show(SparkBillData $sparkBillData)
    {
        abort_if(Gate::denies('spark_bill_data_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sparkBillData->load('sparkbill', 'employee', 'created_by');

        return view('frontend.sparkBillDatas.show', compact('sparkBillData'));
    }

    public function destroy(SparkBillData $sparkBillData)
    {
        abort_if(Gate::denies('spark_bill_data_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sparkBillData->delete();

        return back();
    }

    public function massDestroy(MassDestroySparkBillDataRequest $request)
    {
        $sparkBillDatas = SparkBillData::find(request('ids'));

        foreach ($sparkBillDatas as $sparkBillData) {
            $sparkBillData->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
