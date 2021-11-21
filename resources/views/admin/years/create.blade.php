@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.year.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.years.store") }}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-group">
                <label class="required" for="financial_year">{{ trans('cruds.year.fields.financial_year') }}</label>
                <input class="form-control {{ $errors->has('financial_year') ? 'is-invalid' : '' }}" type="text" name="financial_year" id="financial_year" value="{{ old('financial_year', '') }}" required>
                @if($errors->has('financial_year'))
                    <span class="text-danger">{{ $errors->first('financial_year') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.year.fields.financial_year_helper') }}</span>
            </div>
           <div class="form-group">
                <div class="form-check {{ $errors->has('active') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="active" value="0">
                    <input class="form-check-input" type="checkbox" name="active" id="active" value="1" {{ old('active', 0) == 1 || old('active') === null ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">{{ trans('cruds.year.fields.active') }}</label>
                </div>
                @if($errors->has('active'))
                    <span class="text-danger">{{ $errors->first('active') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.year.fields.active_helper') }}</span>
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