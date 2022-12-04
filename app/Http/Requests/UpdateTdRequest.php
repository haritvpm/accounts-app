<?php

namespace App\Http\Requests;

use App\Models\Td;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTdRequest extends FormRequest
{
    public function authorize()
    {
        return 1; //Gate::allows('td_edit');
    }

    public function rules()
    {
        return [
            'pan' => [
                'string',
                'required',
            ],
            'pen' => [
                'string',
                'required',
            ],
            'name' => [
                'string',
                'required',
            ],
            'gross' => [
                'required',
            ],
            'tds' => [
                'required',
            ],

            'slno' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
