@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.column.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.columns.update", [$column->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required">{{ trans('cruds.column.fields.type') }}</label>
                            <select class="form-control" name="type" id="type" required>
                                <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Column::TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('type', $column->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.column.fields.type_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="head_id">{{ trans('cruds.column.fields.head') }}</label>
                            <select class="form-control select2" name="head_id" id="head_id">
                                @foreach($heads as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('head_id') ? old('head_id') : $column->head->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('head'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('head') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.column.fields.head_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="spark_title">{{ trans('cruds.column.fields.spark_title') }}</label>
                            <input class="form-control" type="text" name="spark_title" id="spark_title" value="{{ old('spark_title', $column->spark_title) }}">
                            @if($errors->has('spark_title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('spark_title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.column.fields.spark_title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="title">{{ trans('cruds.column.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $column->title) }}">
                            @if($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.column.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="fieldmapping">{{ trans('cruds.column.fields.fieldmapping') }}</label>
                            <input class="form-control" type="text" name="fieldmapping" id="fieldmapping" value="{{ old('fieldmapping', $column->fieldmapping) }}" required>
                            @if($errors->has('fieldmapping'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('fieldmapping') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection