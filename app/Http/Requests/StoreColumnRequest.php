<?php

namespace App\Http\Requests;

use App\Models\Column;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreColumnRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_create');
    }

    public function rules()
    {
        return [
            'type' => [
                'required',
            ],
            'spark_title' => [
                'string',
                'nullable',
            ],
            'title' => [
                'string',
                'nullable',
            ],
            'fieldmapping' => [
                'string',
                'required',
                'unique:columns',
            ],
        ];
    }
}
