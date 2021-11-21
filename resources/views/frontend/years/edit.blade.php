@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.year.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.years.update", [$year->id]) }}" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="required" for="financial_year">{{ trans('cruds.year.fields.financial_year') }}</label>
                            <input class="form-control" type="text" name="financial_year" id="financial_year" value="{{ old('financial_year', $year->financial_year) }}" required>
                            @if($errors->has('financial_year'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('financial_year') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.year.fields.financial_year_helper') }}</span>
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
