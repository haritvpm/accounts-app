<?php

namespace App\Http\Requests;

use App\Models\SparkBill;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSparkBillRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('spark_bill_edit');
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
           /*  'sparkcode' => [
                'string',
                'required',
            ], */
            'bill_type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
