@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.salaryBillDetail.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.salary-bill-details.store") }}" enctype="multipart/form-data">
                        {{ method_field('POST') }}
                        {{csrf_field()}}
                        <div class="form-group">
                            <label class="required" for="pay">{{ trans('cruds.salaryBillDetail.fields.pay') }}</label>
                            <input class="form-control" type="number" name="pay" id="pay" value="{{ old('pay', '0') }}" step="0.01" required>
                            @if($errors->has('pay'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('pay') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.salary_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="da">{{ trans('cruds.salaryBillDetail.fields.da') }}</label>
                            <input class="form-control" type="number" name="da" id="da" value="{{ old('da', '0') }}" step="0.01" required>
                            @if($errors->has('da'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('da') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.da_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="hra">{{ trans('cruds.salaryBillDetail.fields.hra') }}</label>
                            <input class="form-control" type="number" name="hra" id="hra" value="{{ old('hra', '0') }}" step="0.01" required>
                            @if($errors->has('hra'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('hra') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.hra_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="other">{{ trans('cruds.salaryBillDetail.fields.other') }}</label>
                            <input class="form-control" type="number" name="other" id="other" value="{{ old('other', '0') }}" step="1" required>
                            @if($errors->has('other'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('other') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.salaryBillDetail.fields.other_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="ota">{{ trans('cruds.salaryBillDetail.fields.ota') }}</label>
                            <input class="form-control" type="number" name="ota" id="ota" value="{{ old('ota', '0') }}" step="0.01">
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
                                    <option value="{{ $id }}" {{ old('year_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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