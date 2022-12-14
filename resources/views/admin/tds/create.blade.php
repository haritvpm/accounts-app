@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
               
                <div class="card-body">
                <!-- <div class="card-title">
                    {{ trans('global.create') }} {{ trans('cruds.td.title_singular') }}
                </div> -->
                    <form method="POST" action="{{ route("admin.tds.store") }}" enctype="multipart/form-data">
                    {{ method_field('POST') }}
                        {{csrf_field()}}
                        <div class="form-group {{ $errors->has('pan') ? 'has-error' : '' }}">
                            <label class="required" for="pan">{{ trans('cruds.td.fields.pan') }}</label>
                            <input class="form-control" type="text" name="pan" id="pan" value="{{ old('pan', '') }}" required>
                            @if($errors->has('pan'))
                                <span class="help-block" role="alert">{{ $errors->first('pan') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.td.fields.pan_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('pen') ? 'has-error' : '' }}">
                            <label class="required" for="pen">{{ trans('cruds.td.fields.pen') }}</label>
                            <input class="form-control" type="text" name="pen" id="pen" value="{{ old('pen', '') }}" required>
                            @if($errors->has('pen'))
                                <span class="help-block" role="alert">{{ $errors->first('pen') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.td.fields.pen_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="required" for="name">{{ trans('cruds.td.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.td.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('gross') ? 'has-error' : '' }}">
                            <label class="required" for="gross">{{ trans('cruds.td.fields.gross') }}</label>
                            <input class="form-control" type="number" name="gross" id="gross" value="{{ old('gross', '') }}" step="0.01" required>
                            @if($errors->has('gross'))
                                <span class="help-block" role="alert">{{ $errors->first('gross') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.td.fields.gross_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('tds') ? 'has-error' : '' }}">
                            <label class="required" for="tds">{{ trans('cruds.td.fields.tds') }}</label>
                            <input class="form-control" type="number" name="tds" id="tds" value="{{ old('tds', '') }}" step="0.01" required>
                            @if($errors->has('tds'))
                                <span class="help-block" role="alert">{{ $errors->first('tds') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.td.fields.tds_helper') }}</span>
                        </div>
                        
                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                            <label class="required" for="date">{{ trans('cruds.taxEntry.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date') }}"
                                required>
                            @if($errors->has('date'))
                            <span class="help-block" role="alert">{{ $errors->first('date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.date_helper') }}</span>
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
