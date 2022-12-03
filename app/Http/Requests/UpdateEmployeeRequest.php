<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return 1;//Gate::allows('employee_edit');
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
                'unique:employees,pen,' . request()->route('employee')->id,
            ],
            'pan' => [
                'string',
                'min:10',
                'required',
                'unique:employees,pan,' . request()->route('employee')->id,
            ],
        ];
    }
}