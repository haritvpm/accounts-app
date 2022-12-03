@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.employee.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.employees.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.employee.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.employee.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="pen">{{ trans('cruds.employee.fields.pen') }}</label>
                            <input class="form-control" type="text" name="pen" id="pen" value="{{ old('pen', '') }}" required>
                            @if($errors->has('pen'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pen') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.employee.fields.pen_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="pan">{{ trans('cruds.employee.fields.pan') }}</label>
                            <input class="form-control" type="text" name="pan" id="pan" value="{{ old('pan', '') }}" required>
                            @if($errors->has('pan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.employee.fields.pan_helper') }}</span>
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