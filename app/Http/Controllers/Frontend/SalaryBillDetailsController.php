<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySalaryBillDetailRequest;
use App\Http\Requests\StoreSalaryBillDetailRequest;
use App\Http\Requests\UpdateSalaryBillDetailRequest;
use App\Models\SalaryBillDetail;
use App\Models\Allocation;
use App\Models\Year;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SalaryBillDetailsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('salary_bill_detail_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        //$curyear = Year::latest()->where('active', 1)->take(1)->pluck('financial_year', 'id');

        $years = Year::latest('financial_year')->where('active', 1);
        $curyear = $years->take(1)->value('financial_year');
       // $years = $years->pluck('financial_year', 'id');
      

       
        $salaryBillDetails = SalaryBillDetail::latest()->with(['year', 'created_by']) 
                    ->whereHas('year', function ($query)   use($curyear) {
                         $query->where('financial_year',  $curyear);
                     })
                    ->where('created_by_id', auth()->id() )
                                        
                    ->get();




        /*$allocations = Allocation::with('year')
           ->whereHas('year', function ($query)   use($curyear) {
                $query->where('financial_year',  $curyear);
            });
        */



        $fields = array("pay", "da", "hra", "other", "ota");

        //$allocation = [];
        $totaluser = [];
        //$balance = [];

        foreach ($fields as $field) {

          //  $allocation[$field] = $allocations->sum($field);
            $totaluser[$field] = $salaryBillDetails->sum($field);
            //$balance[$field] =  $allocation[$field] - $total[$field];
          
        }



       
        return view('frontend.salaryBillDetails.index', compact('salaryBillDetails',  'curyear', /*'allocation',*/ 'totaluser'/*, 'balance'*/));
    }

    public function create()
    {
        abort_if(Gate::denies('salary_bill_detail_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $years = Year::latest('id')->where('active', 1)->pluck('financial_year', 'id');//->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.salaryBillDetails.create', compact('years'));
    }

    public function store(StoreSalaryBillDetailRequest $request)
    {
        $salaryBillDetail = SalaryBillDetail::create($request->all());

        return redirect()->route('frontend.salary-bill-details.index');
    }

    public function edit(SalaryBillDetail $salaryBillDetail)
    {
        abort_if(Gate::denies('salary_bill_detail_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $years = Year::latest('id')->where('active', 1)->pluck('financial_year', 'id');//->prepend(trans('global.pleaseSelect'), '');

        $salaryBillDetail->load('year', 'created_by');

        return view('frontend.salaryBillDetails.edit', compact('years', 'salaryBillDetail'));
    }

    public function update(UpdateSalaryBillDetailRequest $request, SalaryBillDetail $salaryBillDetail)
    {
        $salaryBillDetail->update($request->all());

        return redirect()->route('frontend.salary-bill-details.index');
    }

    public function show(SalaryBillDetail $salaryBillDetail)
    {
        abort_if(Gate::denies('salary_bill_detail_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $salaryBillDetail->load('year', 'created_by');

        return view('frontend.salaryBillDetails.show', compact('salaryBillDetail'));
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