<?php

namespace App\Http\Requests;

use App\Models\SalaryBillDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSalaryBillDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('salary_bill_detail_create');
    }

    public function rules()
    {
        return [
            'pay' => [
                'required',
            ],
            'hra' => [
                'required',
            ],
            'other' => [
                'required',
               
            ],
            'year_id' => [
                'required',
                'integer',
            ],
        ];
    }
}