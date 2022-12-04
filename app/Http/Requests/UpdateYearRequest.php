<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateYearRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('year_edit');
    }

    public function rules()
    {
        return [
            'financial_year' => [
                'string',
                'min:4',
                'required',
                'unique:years,financial_year,'.request()->route('year')->id,
            ],

        ];
    }
}
