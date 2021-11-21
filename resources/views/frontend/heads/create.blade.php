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
                        {{ method_field('POST') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="required" for="head">{{ trans('cruds.head.fields.head') }}</label>
                            <input class="form-control" type="text" name="head" id="head" value="{{ old('head', '') }}" required>
                            @if($errors->has('head'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('head') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection