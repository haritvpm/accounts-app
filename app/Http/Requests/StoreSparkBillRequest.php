<?php

namespace App\Http\Requests;

use App\Models\SparkBill;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSparkBillRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('spark_bill_create');
    }

    public function rules()
    {
        return [
            'date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'acquittance' => [
                'string',
                'nullable',
            ],
            /* 'sparkcode' => [
                'string',
                'nullable',
            ], */
            'bill_type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
