@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.sparkBill.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.spark-bills.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.sparkBill.fields.date') }}</label>
                <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date') }}" required>
                @if($errors->has('date'))
                    <span class="text-danger">{{ $errors->first('date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBill.fields.date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="acquittance">{{ trans('cruds.sparkBill.fields.acquittance') }}</label>
                <input class="form-control {{ $errors->has('acquittance') ? 'is-invalid' : '' }}" type="text" name="acquittance" id="acquittance" value="{{ old('acquittance', '') }}">
                @if($errors->has('acquittance'))
                    <span class="text-danger">{{ $errors->first('acquittance') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBill.fields.acquittance_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sparkcode">{{ trans('cruds.sparkBill.fields.sparkcode') }}</label>
                <input class="form-control {{ $errors->has('sparkcode') ? 'is-invalid' : '' }}" type="text" name="sparkcode" id="sparkcode" value="{{ old('sparkcode', '') }}" required>
                @if($errors->has('sparkcode'))
                    <span class="text-danger">{{ $errors->first('sparkcode') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBill.fields.sparkcode_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="year_id">{{ trans('cruds.sparkBill.fields.year') }}</label>
                <select class="form-control select2 {{ $errors->has('year') ? 'is-invalid' : '' }}" name="year_id" id="year_id">
                    @foreach($years as $id => $entry)
                        <option value="{{ $id }}" {{ old('year_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('year'))
                    <span class="text-danger">{{ $errors->first('year') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBill.fields.year_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bill_type">{{ trans('cruds.sparkBill.fields.bill_type') }}</label>
                <input class="form-control {{ $errors->has('bill_type') ? 'is-invalid' : '' }}" type="text" name="bill_type" id="bill_type" value="{{ old('bill_type', '') }}">
                @if($errors->has('bill_type'))
                    <span class="text-danger">{{ $errors->first('bill_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBill.fields.bill_type_helper') }}</span>
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