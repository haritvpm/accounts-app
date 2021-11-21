@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.head.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.heads.update", [$head->id]) }}" enctype="multipart/form-data">
            {{ method_field('PUT') }}
            {{ csrf_field() }}
            <div class="form-group">
                <label class="required" for="head">{{ trans('cruds.head.fields.head') }}</label>
                <input class="form-control {{ $errors->has('head') ? 'is-invalid' : '' }}" type="text" name="head" id="head" value="{{ old('head', $head->head) }}" required>
                @if($errors->has('head'))
                    <span class="text-danger">{{ $errors->first('head') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.head.fields.head_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
