@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.head.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.heads.index') }}">
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
                                        {{ trans('cruds.head.fields.object_head') }}
                                    </th>
                                    <td>
                                        {{ $head->object_head }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.head.fields.object_head_name') }}
                                    </th>
                                    <td>
                                        {{ $head->object_head_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.head.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $head->user->ddo ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.head.fields.detail_head') }}
                                    </th>
                                    <td>
                                        {{ $head->detail_head }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.heads.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection