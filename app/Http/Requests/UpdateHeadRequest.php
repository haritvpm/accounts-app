<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHeadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('head_edit');
    }

    public function rules()
    {
        return [
            'head' => [
                'string',
                'min:3',
                'required',
                'unique:heads,head,'.request()->route('head')->id,
            ],
        ];
    }
}
