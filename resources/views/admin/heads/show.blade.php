@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.head.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.heads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.head.fields.id') }}
                        </th>
                        <td>
                            {{ $head->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.head.fields.head') }}
                        </th>
                        <td>
                            {{ $head->head }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.heads.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#head_allocations" role="tab" data-toggle="tab">
                {{ trans('cruds.allocation.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="head_allocations">
            @includeIf('admin.heads.relationships.headAllocations', ['allocations' => $head->headAllocations])
        </div>
    </div>
</div>

@endsection