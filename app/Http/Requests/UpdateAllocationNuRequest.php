<?php

namespace App\Http\Requests;

use App\Models\AllocationNu;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAllocationNuRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('allocation_nu_edit');
    }

    public function rules()
    {
        return [
            'amount' => [
                'string',
                'required',
            ],
            'head_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
