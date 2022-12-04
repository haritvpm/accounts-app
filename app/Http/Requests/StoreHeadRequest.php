<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreHeadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('head_create');
    }

    public function rules()
    {
        return [
            'head' => [
                'string',
                'min:3',
                'required',
                'unique:heads',
            ],
        ];
    }
}
