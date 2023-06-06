<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySparkBillRequest;
use App\Http\Requests\StoreSparkBillRequest;
use App\Http\Requests\UpdateSparkBillRequest;
use App\Models\SparkBill;
use App\Models\Year;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SparkBillController extends Controller
{
    public function index()
    {
        //abort_if(Gate::denies('spark_bill_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sparkBills = SparkBill::with(['year', 'created_by'])->get();

        return view('admin.sparkBills.index', compact('sparkBills'));
    }

    public function create()
    {
        //abort_if(Gate::denies('spark_bill_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $years = Year::pluck('financial_year', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.sparkBills.create', compact('years'));
    }

    public function store(StoreSparkBillRequest $request)
    {
        $sparkBill = SparkBill::create($request->all());

        return redirect()->route('admin.spark-bills.index');
    }

    public function edit(SparkBill $sparkBill)
    {
       // abort_if(Gate::denies('spark_bill_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $years = Year::pluck('financial_year', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sparkBill->load('year', 'created_by');

        return view('admin.sparkBills.edit', compact('sparkBill', 'years'));
    }

    public function update(UpdateSparkBillRequest $request, SparkBill $sparkBill)
    {
        $sparkBill->update($request->all());

        return redirect()->route('admin.spark-bills.index');
    }

    public function show(SparkBill $sparkBill)
    {
        //abort_if(Gate::denies('spark_bill_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sparkBill->load('year', 'created_by');

        return view('admin.sparkBills.show', compact('sparkBill'));
    }

    public function destroy(SparkBill $sparkBill)
    {
       // abort_if(Gate::denies('spark_bill_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sparkBill->delete();

        return back();
    }

    public function massDestroy(MassDestroySparkBillRequest $request)
    {
        $sparkBills = SparkBill::find(request('ids'));

        foreach ($sparkBills as $sparkBill) {
            $sparkBill->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
