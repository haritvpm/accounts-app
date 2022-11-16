<?php

namespace App\Http\Controllers\Admin;
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

        $months = array("Apr"=>"", "May"=>"", "Jun"=>"", "Jul"=>"", "Aug"=>"","Sep"=>"","Oct"=>"", "Nov"=>"","Dec"=>"","Jan"=>"","Feb"=>"","Mar"=>"");

        foreach ($salaryBillDetails as $s) {
           
            $month =$s->created_at->format('M');
            $usr = strtoupper($s->created_by->name);
           
            //split coma sep string to array
            $submitted =  explode(',', $months[$month] );
            
             if(!in_array($usr, $submitted)){
                array_push($submitted,$usr );
             }
             sort($submitted);

             $months[$month] =  trim(implode(',', $submitted), ',') ;
        }

        
        return view('home', compact('curyear', 'allocation', 'total', 'balance','months'));
    }
}