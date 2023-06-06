<?php

namespace App\Http\Requests;

use App\Models\Head;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateHeadRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_edit');
    }

    public function rules()
    {
        return [
            'object_head' => [
                'string',
                'required',
                'unique:heads,object_head,' . request()->route('head')->id,
            ],
            'object_head_name' => [
                'string',
                'required',
                'unique:heads,object_head_name,' . request()->route('head')->id,
            ],
         /*    'user_id' => [
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
