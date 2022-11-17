<?php

namespace App\Http\Requests;

use App\Models\TaxEntry;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTaxEntryRequest extends FormRequest
{
    public function authorize()
    {
        return 1; //Gate::allows('tax_entry_edit');
    }

    public function rules()
    {
        return [
            'date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'innerfile' => [
                'required',
            ],
            'deductionfile' => [
                'required',
            ],
        ];
    }
}