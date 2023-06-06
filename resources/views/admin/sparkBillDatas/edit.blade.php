@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.sparkBillData.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.spark-bill-datas.update", [$sparkBillData->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="net">{{ trans('cruds.sparkBillData.fields.net') }}</label>
                <input class="form-control {{ $errors->has('net') ? 'is-invalid' : '' }}" type="number" name="net" id="net" value="{{ old('net', $sparkBillData->net) }}" step="0.01">
                @if($errors->has('net'))
                    <span class="text-danger">{{ $errors->first('net') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.net_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sparkbill_id">{{ trans('cruds.sparkBillData.fields.sparkbill') }}</label>
                <select class="form-control select2 {{ $errors->has('sparkbill') ? 'is-invalid' : '' }}" name="sparkbill_id" id="sparkbill_id" required>
                    @foreach($sparkbills as $id => $entry)
                        <option value="{{ $id }}" {{ (old('sparkbill_id') ? old('sparkbill_id') : $sparkBillData->sparkbill->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('sparkbill'))
                    <span class="text-danger">{{ $errors->first('sparkbill') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.sparkbill_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="employee_id">{{ trans('cruds.sparkBillData.fields.employee') }}</label>
                <select class="form-control select2 {{ $errors->has('employee') ? 'is-invalid' : '' }}" name="employee_id" id="employee_id">
                    @foreach($employees as $id => $entry)
                        <option value="{{ $id }}" {{ (old('employee_id') ? old('employee_id') : $sparkBillData->employee->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('employee'))
                    <span class="text-danger">{{ $errors->first('employee') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.employee_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_1">{{ trans('cruds.sparkBillData.fields.field_1') }}</label>
                <input class="form-control {{ $errors->has('field_1') ? 'is-invalid' : '' }}" type="number" name="field_1" id="field_1" value="{{ old('field_1', $sparkBillData->field_1) }}" step="0.01">
                @if($errors->has('field_1'))
                    <span class="text-danger">{{ $errors->first('field_1') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_1_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_2">{{ trans('cruds.sparkBillData.fields.field_2') }}</label>
                <input class="form-control {{ $errors->has('field_2') ? 'is-invalid' : '' }}" type="number" name="field_2" id="field_2" value="{{ old('field_2', $sparkBillData->field_2) }}" step="0.01">
                @if($errors->has('field_2'))
                    <span class="text-danger">{{ $errors->first('field_2') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_3">{{ trans('cruds.sparkBillData.fields.field_3') }}</label>
                <input class="form-control {{ $errors->has('field_3') ? 'is-invalid' : '' }}" type="number" name="field_3" id="field_3" value="{{ old('field_3', $sparkBillData->field_3) }}" step="0.01">
                @if($errors->has('field_3'))
                    <span class="text-danger">{{ $errors->first('field_3') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_3_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_4">{{ trans('cruds.sparkBillData.fields.field_4') }}</label>
                <input class="form-control {{ $errors->has('field_4') ? 'is-invalid' : '' }}" type="number" name="field_4" id="field_4" value="{{ old('field_4', $sparkBillData->field_4) }}" step="0.01">
                @if($errors->has('field_4'))
                    <span class="text-danger">{{ $errors->first('field_4') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_4_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_5">{{ trans('cruds.sparkBillData.fields.field_5') }}</label>
                <input class="form-control {{ $errors->has('field_5') ? 'is-invalid' : '' }}" type="number" name="field_5" id="field_5" value="{{ old('field_5', $sparkBillData->field_5) }}" step="0.01">
                @if($errors->has('field_5'))
                    <span class="text-danger">{{ $errors->first('field_5') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_5_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_6">{{ trans('cruds.sparkBillData.fields.field_6') }}</label>
                <input class="form-control {{ $errors->has('field_6') ? 'is-invalid' : '' }}" type="number" name="field_6" id="field_6" value="{{ old('field_6', $sparkBillData->field_6) }}" step="0.01">
                @if($errors->has('field_6'))
                    <span class="text-danger">{{ $errors->first('field_6') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_6_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_7">{{ trans('cruds.sparkBillData.fields.field_7') }}</label>
                <input class="form-control {{ $errors->has('field_7') ? 'is-invalid' : '' }}" type="number" name="field_7" id="field_7" value="{{ old('field_7', $sparkBillData->field_7) }}" step="0.01">
                @if($errors->has('field_7'))
                    <span class="text-danger">{{ $errors->first('field_7') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_7_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_8">{{ trans('cruds.sparkBillData.fields.field_8') }}</label>
                <input class="form-control {{ $errors->has('field_8') ? 'is-invalid' : '' }}" type="number" name="field_8" id="field_8" value="{{ old('field_8', $sparkBillData->field_8) }}" step="0.01">
                @if($errors->has('field_8'))
                    <span class="text-danger">{{ $errors->first('field_8') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_8_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_9">{{ trans('cruds.sparkBillData.fields.field_9') }}</label>
                <input class="form-control {{ $errors->has('field_9') ? 'is-invalid' : '' }}" type="number" name="field_9" id="field_9" value="{{ old('field_9', $sparkBillData->field_9) }}" step="0.01">
                @if($errors->has('field_9'))
                    <span class="text-danger">{{ $errors->first('field_9') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_9_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_10">{{ trans('cruds.sparkBillData.fields.field_10') }}</label>
                <input class="form-control {{ $errors->has('field_10') ? 'is-invalid' : '' }}" type="number" name="field_10" id="field_10" value="{{ old('field_10', $sparkBillData->field_10) }}" step="0.01">
                @if($errors->has('field_10'))
                    <span class="text-danger">{{ $errors->first('field_10') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_10_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_11">{{ trans('cruds.sparkBillData.fields.field_11') }}</label>
                <input class="form-control {{ $errors->has('field_11') ? 'is-invalid' : '' }}" type="number" name="field_11" id="field_11" value="{{ old('field_11', $sparkBillData->field_11) }}" step="0.01">
                @if($errors->has('field_11'))
                    <span class="text-danger">{{ $errors->first('field_11') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sparkBillData.fields.field_11_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="field_12">{{ trans('cruds.sparkBillData.fields.field_12') }}</label>
                <input class="form-control {{ $errors->has('field_12') ? 'is-invalid' : '' }}" type="number" name="field_12" id="field_12" value="{{ old('field_12', $sparkBillData->field_12) }}" step="0.01">
                @if($errors->has('field_12'))
                    <span class="text-danger">{{ $errors->first('field_12') }}</span>
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



@endsection