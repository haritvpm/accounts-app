<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

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
