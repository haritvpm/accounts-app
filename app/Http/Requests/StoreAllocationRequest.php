<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreAllocationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('allocation_create');
    }

    public function rules()
    {
        return [
            'pay' => [
                'required',
            ],
            'da' => [
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
