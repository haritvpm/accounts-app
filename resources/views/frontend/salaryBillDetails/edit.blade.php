@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.salaryBillDetail.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.salary-bill-details.update", [$salaryBillDetail->id]) }}" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        {{csrf_field()}}
                        <div class="form-group">
                            <label class="required" for="salary">{{ trans('cruds.salaryBillDetail.fields.salary') }}</label>
                            <input class="form-control" type="number" name="salary" id="salary" value="{{ old('salary', $salaryBillDetail->salary) }}" step="0.01" required>
                            @if($errors->has('salary'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('salary') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.salary_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="da">{{ trans('cruds.salaryBillDetail.fields.da') }}</label>
                            <input class="form-control" type="number" name="da" id="da" value="{{ old('da', $salaryBillDetail->da) }}" step="0.01" required>
                            @if($errors->has('da'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('da') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.da_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="hra">{{ trans('cruds.salaryBillDetail.fields.hra') }}</label>
                            <input class="form-control" type="number" name="hra" id="hra" value="{{ old('hra', $salaryBillDetail->hra) }}" step="0.01" required>
                            @if($errors->has('hra'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('hra') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.hra_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="other">{{ trans('cruds.salaryBillDetail.fields.other') }}</label>
                            <input class="form-control" type="number" name="other" id="other" value="{{ old('other', $salaryBillDetail->other) }}" step="1" required>
                            @if($errors->has('other'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('other') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.other_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="ota">{{ trans('cruds.salaryBillDetail.fields.ota') }}</label>
                            <input class="form-control" type="number" name="ota" id="ota" value="{{ old('ota', $salaryBillDetail->ota) }}" step="0.01">
                            @if($errors->has('ota'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ota') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.ota_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="year_id">{{ trans('cruds.salaryBillDetail.fields.year') }}</label>
                            <select class="form-control select2" name="year_id" id="year_id" required>
                                @foreach($years as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('year_id') ? old('year_id') : $salaryBillDetail->year->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('year'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('year') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection