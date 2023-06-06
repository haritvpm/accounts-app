<?php

namespace App\Http\Requests;

use App\Models\Column;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateColumnRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_edit');
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
                'unique:columns,fieldmapping,' . request()->route('column')->id,
            ],
        ];
    }
}
