<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('employee_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'pen' => [
                'string',
                'min:6',
                'required',
                'unique:employees',
            ],
            'pan' => [
                'string',
                'min:10',
                'required',
                'unique:employees',
            ],
        ];
    }
}