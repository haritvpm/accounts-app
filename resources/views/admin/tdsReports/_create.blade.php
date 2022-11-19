@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.tdsReport.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.tds-reports.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('year') ? 'has-error' : '' }}">
                            <label class="required" for="year">{{ trans('cruds.tdsReport.fields.year') }}</label>
                            <input class="form-control" type="number" name="year" id="year" value="{{ old('year', '2022') }}" step="1" required>
                            @if($errors->has('year'))
                                <span class="help-block" role="alert">{{ $errors->first('year') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.tdsReport.fields.year_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('period') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.tdsReport.fields.period') }}</label>
                            @foreach(App\Models\TdsReport::PERIOD_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="period_{{ $key }}" name="period" value="{{ $key }}" {{ old('period', '0') === (string) $key ? 'checked' : '' }} required>
                                    <label for="period_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('period'))
                                <span class="help-block" role="alert">{{ $errors->first('period') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.tdsReport.fields.period_helper') }}</span>
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