@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.head.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.heads.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="object_head">{{ trans('cruds.head.fields.object_head') }}</label>
                            <input class="form-control" type="text" name="object_head" id="object_head" value="{{ old('object_head', '') }}" required>
                            @if($errors->has('object_head'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('object_head') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.head.fields.object_head_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="object_head_name">{{ trans('cruds.head.fields.object_head_name') }}</label>
                            <input class="form-control" type="text" name="object_head_name" id="object_head_name" value="{{ old('object_head_name', '') }}" required>
                            @if($errors->has('object_head_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('object_head_name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.head.fields.object_head_name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.head.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.head.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="detail_head">{{ trans('cruds.head.fields.detail_head') }}</label>
                            <input class="form-control" type="text" name="detail_head" id="detail_head" value="{{ old('detail_head', '') }}" required>
                            @if($errors->has('detail_head'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('detail_head') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.head.fields.detail_head_helper') }}</span>
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