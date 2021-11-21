@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.allocation.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.allocations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.allocation.fields.id') }}
                        </th>
                        <td>
                            {{ $allocation->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.allocation.fields.pay') }}
                        </th>
                        <td>
                            {{ $allocation->pay }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.allocation.fields.da') }}
                        </th>
                        <td>
                            {{ $allocation->da }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.allocation.fields.hra') }}
                        </th>
                        <td>
                            {{ $allocation->hra }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.allocation.fields.ota') }}
                        </th>
                        <td>
                            {{ $allocation->ota }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.allocation.fields.other') }}
                        </th>
                        <td>
                            {{ $allocation->other }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.allocation.fields.year') }}
                        </th>
                        <td>
                            {{ $allocation->year->financial_year ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.allocations.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection