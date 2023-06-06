@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.sparkBill.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.spark-bills.update", [$sparkBill->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="date">{{ trans('cruds.sparkBill.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date', $sparkBill->date) }}" required>
                            @if($errors->has('date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBill.fields.date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="acquittance">{{ trans('cruds.sparkBill.fields.acquittance') }}</label>
                            <input class="form-control" type="text" name="acquittance" id="acquittance" value="{{ old('acquittance', $sparkBill->acquittance) }}">
                            @if($errors->has('acquittance'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('acquittance') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBill.fields.acquittance_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="sparkcode">{{ trans('cruds.sparkBill.fields.sparkcode') }}</label>
                            <input class="form-control" type="text" name="sparkcode" id="sparkcode" value="{{ old('sparkcode', $sparkBill->sparkcode) }}" required>
                            @if($errors->has('sparkcode'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sparkcode') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBill.fields.sparkcode_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="year_id">{{ trans('cruds.sparkBill.fields.year') }}</label>
                            <select class="form-control select2" name="year_id" id="year_id">
                                @foreach($years as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('year_id') ? old('year_id') : $sparkBill->year->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('year'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('year') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBill.fields.year_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="bill_type">{{ trans('cruds.sparkBill.fields.bill_type') }}</label>
                            <input class="form-control" type="text" name="bill_type" id="bill_type" value="{{ old('bill_type', $sparkBill->bill_type) }}">
                            @if($errors->has('bill_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bill_type') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection