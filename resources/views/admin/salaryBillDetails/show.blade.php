@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.salaryBillDetail.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.salary-bill-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.id') }}
                        </th>
                        <td>
                            {{ $salaryBillDetail->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.pay') }}
                        </th>
                        <td>
                            {{ $salaryBillDetail->pay }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.da') }}
                        </th>
                        <td>
                            {{ $salaryBillDetail->da }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.hra') }}
                        </th>
                        <td>
                            {{ $salaryBillDetail->hra }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.other') }}
                        </th>
                        <td>
                            {{ $salaryBillDetail->other }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.ota') }}
                        </th>
                        <td>
                            {{ $salaryBillDetail->ota }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.year') }}
                        </th>
                        <td>
                            {{ $salaryBillDetail->year->financial_year ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.salary-bill-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection