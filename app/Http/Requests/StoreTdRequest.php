<?php

namespace App\Http\Requests;

use App\Models\Td;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTdRequest extends FormRequest
{
    public function authorize()
    {
        return 1;//Gate::allows('td_create');
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
           /*  'date_id' => [
                'required',
                'integer',
            ], */
            'date' => [
                'required',
                'date_format:' . config('panel.date_format'),
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