@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.column.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.columns.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.column.fields.id') }}
                        </th>
                        <td>
                            {{ $column->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.column.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\Column::TYPE_SELECT[$column->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.column.fields.head') }}
                        </th>
                        <td>
                            {{ $column->head->object_head ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.column.fields.spark_title') }}
                        </th>
                        <td>
                            {{ $column->spark_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.column.fields.title') }}
                        </th>
                        <td>
                            {{ $column->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.column.fields.fieldmapping') }}
                        </th>
                        <td>
                            {{ $column->fieldmapping }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.columns.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection