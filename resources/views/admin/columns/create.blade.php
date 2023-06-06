@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.column.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.columns.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required">{{ trans('cruds.column.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Column::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <span class="text-danger">{{ $errors->first('type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.column.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="head_id">{{ trans('cruds.column.fields.head') }}</label>
                <select class="form-control {{ $errors->has('head') ? 'is-invalid' : '' }}" name="head_id" id="head_id">
                    @foreach($heads as $id => $entry)
                        <option value="{{ $id }}" {{ old('head_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('head'))
                    <span class="text-danger">{{ $errors->first('head') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.column.fields.head_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="spark_title">{{ trans('cruds.column.fields.spark_title') }}</label>
                <input class="form-control {{ $errors->has('spark_title') ? 'is-invalid' : '' }}" type="text" name="spark_title" id="spark_title" value="{{ old('spark_title', '') }}">
                @if($errors->has('spark_title'))
                    <span class="text-danger">{{ $errors->first('spark_title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.column.fields.spark_title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="title">{{ trans('cruds.column.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}">
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.column.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="fieldmapping">{{ trans('cruds.column.fields.fieldmapping') }}</label>
                <input class="form-control {{ $errors->has('fieldmapping') ? 'is-invalid' : '' }}" type="text" name="fieldmapping" id="fieldmapping" value="{{ old('fieldmapping', '') }}" required>
                @if($errors->has('fieldmapping'))
                    <span class="text-danger">{{ $errors->first('fieldmapping') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.column.fields.fieldmapping_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection