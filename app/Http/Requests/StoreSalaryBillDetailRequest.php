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
            'salary' => [
                'required',
            ],
            'hra' => [
                'required',
            ],
            'other' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'year_id' => [
                'required',
                'integer',
            ],
        ];
    }
}