<?php

namespace App\Http\Controllers\Frontend;

use App\Models\SalaryBillDetail;
use App\Models\Allocation;
use App\Models\Year;

class HomeController
{
    public function index()
    {

        $years = Year::latest('financial_year')->where('active', 1);
        $curyear = $years->take(1)->value('financial_year');
       // $years = $years->pluck('financial_year', 'id');
      

        $allocations = Allocation::with('year')
           ->whereHas('year', function ($query)   use($curyear) {
                $query->where('financial_year',  $curyear);
            });

         $salaryBillDetails = SalaryBillDetail::latest()->with(['year', 'created_by']) 
                    ->whereHas('year', function ($query)   use($curyear) {
                         $query->where('financial_year',  $curyear);
                     })->get();


        $fields = array("pay", "da", "hra", "other", "ota");

        $allocation = [];
        $total = [];
        $balance = [];

        foreach ($fields as $field) {

            $allocation[$field] = $allocations->sum($field);
            $total[$field] = $salaryBillDetails->sum($field);
            $balance[$field] =  $allocation[$field] - $total[$field];
          
        }


        return view('frontend.home', compact('curyear', 'allocation', 'total', 'balance'));
    }
}