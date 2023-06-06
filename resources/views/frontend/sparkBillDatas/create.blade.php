@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.sparkBillData.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.spark-bill-datas.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="net">{{ trans('cruds.sparkBillData.fields.net') }}</label>
                            <input class="form-control" type="number" name="net" id="net" value="{{ old('net', '') }}" step="0.01">
                            @if($errors->has('net'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('net') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.net_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="sparkbill_id">{{ trans('cruds.sparkBillData.fields.sparkbill') }}</label>
                            <select class="form-control select2" name="sparkbill_id" id="sparkbill_id" required>
                                @foreach($sparkbills as $id => $entry)
                                    <option value="{{ $id }}" {{ old('sparkbill_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('sparkbill'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sparkbill') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.sparkbill_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="employee_id">{{ trans('cruds.sparkBillData.fields.employee') }}</label>
                            <select class="form-control select2" name="employee_id" id="employee_id">
                                @foreach($employees as $id => $entry)
                                    <option value="{{ $id }}" {{ old('employee_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('employee'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('employee') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.employee_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_1">{{ trans('cruds.sparkBillData.fields.field_1') }}</label>
                            <input class="form-control" type="number" name="field_1" id="field_1" value="{{ old('field_1', '') }}" step="0.01">
                            @if($errors->has('field_1'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_1') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_1_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_2">{{ trans('cruds.sparkBillData.fields.field_2') }}</label>
                            <input class="form-control" type="number" name="field_2" id="field_2" value="{{ old('field_2', '') }}" step="0.01">
                            @if($errors->has('field_2'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_2') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_2_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_3">{{ trans('cruds.sparkBillData.fields.field_3') }}</label>
                            <input class="form-control" type="number" name="field_3" id="field_3" value="{{ old('field_3', '') }}" step="0.01">
                            @if($errors->has('field_3'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_3') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_3_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_4">{{ trans('cruds.sparkBillData.fields.field_4') }}</label>
                            <input class="form-control" type="number" name="field_4" id="field_4" value="{{ old('field_4', '') }}" step="0.01">
                            @if($errors->has('field_4'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_4') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_4_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_5">{{ trans('cruds.sparkBillData.fields.field_5') }}</label>
                            <input class="form-control" type="number" name="field_5" id="field_5" value="{{ old('field_5', '') }}" step="0.01">
                            @if($errors->has('field_5'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_5') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_5_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_6">{{ trans('cruds.sparkBillData.fields.field_6') }}</label>
                            <input class="form-control" type="number" name="field_6" id="field_6" value="{{ old('field_6', '') }}" step="0.01">
                            @if($errors->has('field_6'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_6') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_6_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_7">{{ trans('cruds.sparkBillData.fields.field_7') }}</label>
                            <input class="form-control" type="number" name="field_7" id="field_7" value="{{ old('field_7', '') }}" step="0.01">
                            @if($errors->has('field_7'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_7') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_7_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_8">{{ trans('cruds.sparkBillData.fields.field_8') }}</label>
                            <input class="form-control" type="number" name="field_8" id="field_8" value="{{ old('field_8', '') }}" step="0.01">
                            @if($errors->has('field_8'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_8') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_8_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_9">{{ trans('cruds.sparkBillData.fields.field_9') }}</label>
                            <input class="form-control" type="number" name="field_9" id="field_9" value="{{ old('field_9', '') }}" step="0.01">
                            @if($errors->has('field_9'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_9') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_9_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_10">{{ trans('cruds.sparkBillData.fields.field_10') }}</label>
                            <input class="form-control" type="number" name="field_10" id="field_10" value="{{ old('field_10', '') }}" step="0.01">
                            @if($errors->has('field_10'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_10') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_10_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_11">{{ trans('cruds.sparkBillData.fields.field_11') }}</label>
                            <input class="form-control" type="number" name="field_11" id="field_11" value="{{ old('field_11', '') }}" step="0.01">
                            @if($errors->has('field_11'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_11') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_11_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="field_12">{{ trans('cruds.sparkBillData.fields.field_12') }}</label>
                            <input class="form-control" type="number" name="field_12" id="field_12" value="{{ old('field_12', '') }}" step="0.01">
                            @if($errors->has('field_12'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('field_12') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_12_helper') }}</span>
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