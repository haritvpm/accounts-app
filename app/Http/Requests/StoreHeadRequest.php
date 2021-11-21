<?php

namespace App\Http\Requests;

use App\Models\Head;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

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