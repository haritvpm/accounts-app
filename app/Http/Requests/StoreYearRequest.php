<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreYearRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('year_create');
    }

    public function rules()
    {
        return [
            'financial_year' => [
                'string',
                'min:4',
                'required',
                'unique:years',
            ],
        ];
    }
}
