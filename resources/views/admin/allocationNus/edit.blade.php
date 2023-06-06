@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.allocationNu.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.allocation-nus.update", [$allocationNu->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="amount">{{ trans('cruds.allocationNu.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="text" name="amount" id="amount" value="{{ old('amount', $allocationNu->amount) }}" required>
                @if($errors->has('amount'))
                    <span class="text-danger">{{ $errors->first('amount') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.allocationNu.fields.amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="year_id">{{ trans('cruds.allocationNu.fields.year') }}</label>
                <select class="form-control select2 {{ $errors->has('year') ? 'is-invalid' : '' }}" name="year_id" id="year_id">
                    @foreach($years as $id => $entry)
                        <option value="{{ $id }}" {{ (old('year_id') ? old('year_id') : $allocationNu->year->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('year'))
                    <span class="text-danger">{{ $errors->first('year') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.allocationNu.fields.year_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="head_id">{{ trans('cruds.allocationNu.fields.head') }}</label>
                <select class="form-control select2 {{ $errors->has('head') ? 'is-invalid' : '' }}" name="head_id" id="head_id" required>
                    @foreach($heads as $id => $entry)
                        <option value="{{ $id }}" {{ (old('head_id') ? old('head_id') : $allocationNu->head->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('head'))
                    <span class="text-danger">{{ $errors->first('head') }}</span>
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



@endsection