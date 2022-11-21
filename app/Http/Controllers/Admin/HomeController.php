<?php

namespace App\Http\Controllers\Admin;
use App\Models\SalaryBillDetail;
use App\Models\Allocation;
use App\Models\Year;
use App\Models\TaxEntry;
use Carbon\Carbon;

class HomeController
{
    public function index()
    {


        $years = Year::latest('financial_year')->where('active', 1)->pluck('financial_year');
        
        //$years2 = $years->take(2);
       // $years = $years->pluck('financial_year', 'id');
       $curyear= '';
       
       if(count($years)){
        $curyear = $years[0];
       }
     //  $lastyear= $years2->offsetGet(1)->value('financial_year');
     
        $allocations = Allocation::with('year')
           ->whereHas('year', function ($query)   use($curyear) {
                $query->where('financial_year',  $curyear);
            });

        $salaryBillDetails = SalaryBillDetail::latest()->with(['year', 'created_by']) 
                            ->whereBetween('created_at',[Carbon::now()->subMonths(14), Carbon::now()]
                            )->get();

      
        $taxEntryDetails = TaxEntry::latest()->with(['created_by'])
                     ->whereNotNull('created_by_id') //ignore admin entries
                     ->whereBetween('date',[Carbon::now()->subMonths(14), Carbon::now()])
                     ->get();


        $fields = array("pay", "da", "hra", "other", "ota");

        $allocation = [];
        $total = [];
        $balance = [];

        foreach ($fields as $field) {

            $allocation[$field] = $allocations->sum($field);
            $total[$field] = $salaryBillDetails->sum($field);
            $balance[$field] =  $allocation[$field] - $total[$field];
          
        }

        $months = array();
        $monthsTDS= array();

        for ($i = 0; $i <= 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $months[$date->format('Y-M')]='';
            $monthsTDS[$date->format('Y-M')]='';
        }
        
        foreach ($salaryBillDetails as $s) {
           
            $yearmonth =$s->created_at->format('Y-M');
            $usr = strtoupper($s->created_by->name);
           
            //split coma sep string to array
            $submitted =  explode(',', $months[$yearmonth] );
            
             if(!in_array($usr, $submitted)){
                array_push($submitted,$usr );
             }
             sort($submitted);

             $months[$yearmonth] =  trim(implode(',', $submitted), ',') ;
        }
       
        foreach ( $taxEntryDetails as $s) {
                       
            $yearmonth = Carbon::createFromFormat('d/m/Y', $s->date )->format('Y-M');

            $usr = strtoupper($s->created_by->name);
           
            //split coma sep string to array
            $submitted =  explode(',', $monthsTDS[$yearmonth] );
            
             if(!in_array($usr, $submitted)){
                array_push($submitted,$usr );
             }
             sort($submitted);

             $monthsTDS[$yearmonth] =  trim(implode(',', $submitted), ',') ;
        }

       

        
        return view('home', compact('curyear', 'allocation', 'total', 'balance','months', 'monthsTDS'));
    }
}