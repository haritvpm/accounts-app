@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.sparkBillData.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.spark-bill-datas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.id') }}
                        </th>
                        <td>
                            {{ $sparkBillData->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.net') }}
                        </th>
                        <td>
                            {{ $sparkBillData->net }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.sparkbill') }}
                        </th>
                        <td>
                            {{ $sparkBillData->sparkbill->sparkcode ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.employee') }}
                        </th>
                        <td>
                            {{ $sparkBillData->employee->pen ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_1') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_1 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_2') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_2 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_3') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_3 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_4') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_4 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_5') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_5 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_6') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_6 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_7') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_7 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_8') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_8 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_9') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_9 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_10') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_10 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_11') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_11 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sparkBillData.fields.field_12') }}
                        </th>
                        <td>
                            {{ $sparkBillData->field_12 }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.spark-bill-datas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection