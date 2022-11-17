@extends('layouts.frontend')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.taxEntry.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route('frontend.tax-entries.update', [$taxEntry->id]) }}"
                      enctype="multipart/form-data">
                      {{ method_field('PUT') }}
                        {{csrf_field()}}
                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                            <label class="required" for="date">{{ trans('cruds.taxEntry.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date"
                                value="{{ old('date', $taxEntry->date) }}" readonly required>
                            @if($errors->has('date'))
                            <span class="help-block" role="alert">{{ $errors->first('date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.taxEntry.fields.status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value disabled {{ old('status', null)===null ? 'selected' : '' }}>{{
                                    trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\TaxEntry::STATUS_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $taxEntry->status) === (string) $key ?
                                    'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                            <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.status_helper') }}</span>
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