@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.salaryBillDetail.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.salary-bill-details.store") }}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-group">
                <label class="required" for="salary">{{ trans('cruds.salaryBillDetail.fields.salary') }}</label>
                <input class="form-control {{ $errors->has('pay') ? 'is-invalid' : '' }}" type="number" name="salary" id="salary" value="{{ old('pay', '0') }}" step="0.01" required>
                @if($errors->has('pay'))
                    <span class="text-danger">{{ $errors->first('pay') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.salary_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="da">{{ trans('cruds.salaryBillDetail.fields.da') }}</label>
                <input class="form-control {{ $errors->has('da') ? 'is-invalid' : '' }}" type="number" name="da" id="da" value="{{ old('da', '0') }}" step="0.01" required>
                @if($errors->has('da'))
                    <span class="text-danger">{{ $errors->first('da') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.da_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="hra">{{ trans('cruds.salaryBillDetail.fields.hra') }}</label>
                <input class="form-control {{ $errors->has('hra') ? 'is-invalid' : '' }}" type="number" name="hra" id="hra" value="{{ old('hra', '0') }}" step="0.01" required>
                @if($errors->has('hra'))
                    <span class="text-danger">{{ $errors->first('hra') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.hra_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="other">{{ trans('cruds.salaryBillDetail.fields.other') }}</label>
                <input class="form-control {{ $errors->has('other') ? 'is-invalid' : '' }}" type="number" name="other" id="other" value="{{ old('other', '0') }}" step="1" required>
                @if($errors->has('other'))
                    <span class="text-danger">{{ $errors->first('other') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.other_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ota">{{ trans('cruds.salaryBillDetail.fields.ota') }}</label>
                <input class="form-control {{ $errors->has('ota') ? 'is-invalid' : '' }}" type="number" name="ota" id="ota" value="{{ old('ota', '0') }}" step="0.01">
                @if($errors->has('ota'))
                    <span class="text-danger">{{ $errors->first('ota') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.ota_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="year_id">{{ trans('cruds.salaryBillDetail.fields.year') }}</label>
                <select class="form-control select2 {{ $errors->has('year') ? 'is-invalid' : '' }}" name="year_id" id="year_id" required>
                    @foreach($years as $id => $entry)
                        <option value="{{ $id }}" {{ old('year_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('year'))
                    <span class="text-danger">{{ $errors->first('year') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.year_helper') }}</span>
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