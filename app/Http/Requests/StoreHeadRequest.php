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
        return Gate::allows('user_create');
    }

    public function rules()
    {
        return [
            'object_head' => [
                'string',
                'required',
                'unique:heads',
            ],
            'object_head_name' => [
                'string',
                'required',
                'unique:heads',
            ],
            /* 'user_id' => [
                'required',
                'integer',
            ], */
            'detail_head' => [
                'string',
                'required',
            ],
        ];
    }
}
