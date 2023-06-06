<?php

namespace App\Http\Requests;

use App\Models\SparkBillData;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSparkBillDataRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('spark_bill_data_edit');
    }

    public function rules()
    {
        return [
            'sparkbill_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
