<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaxEntryRequest extends FormRequest
{
    public function authorize()
    {
        return true; //Gate::allows('tax_entry_create');
    }

    public function rules()
    {
        return [
            'date' => [
                'required',
                'date_format:'.config('panel.date_format'),
            ],
            'file1' => [
                'required',
            ],
            /*  'file2' => [
                'required',
            ], */
        ];
    }
}
