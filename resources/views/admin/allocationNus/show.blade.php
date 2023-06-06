@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.allocationNu.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.allocation-nus.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.allocationNu.fields.id') }}
                        </th>
                        <td>
                            {{ $allocationNu->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.allocationNu.fields.amount') }}
                        </th>
                        <td>
                            {{ $allocationNu->amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.allocationNu.fields.year') }}
                        </th>
                        <td>
                            {{ $allocationNu->year->financial_year ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.allocationNu.fields.head') }}
                        </th>
                        <td>
                            {{ $allocationNu->head->object_head ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.allocation-nus.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection