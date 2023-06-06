@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.allocationNu.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.allocation-nus.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="amount">{{ trans('cruds.allocationNu.fields.amount') }}</label>
                            <input class="form-control" type="text" name="amount" id="amount" value="{{ old('amount', '') }}" required>
                            @if($errors->has('amount'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('amount') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.allocationNu.fields.amount_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="year_id">{{ trans('cruds.allocationNu.fields.year') }}</label>
                            <select class="form-control select2" name="year_id" id="year_id">
                                @foreach($years as $id => $entry)
                                    <option value="{{ $id }}" {{ old('year_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('year'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('year') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.allocationNu.fields.year_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="head_id">{{ trans('cruds.allocationNu.fields.head') }}</label>
                            <select class="form-control select2" name="head_id" id="head_id" required>
                                @foreach($heads as $id => $entry)
                                    <option value="{{ $id }}" {{ old('head_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('head'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('head') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.allocationNu.fields.head_helper') }}</span>
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