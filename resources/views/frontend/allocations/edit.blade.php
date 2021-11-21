@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.allocation.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.allocations.update", [$allocation->id]) }}" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="required" for="allocation">{{ trans('cruds.allocation.fields.allocation') }}</label>
                            <input class="form-control" type="text" name="allocation" id="allocation" value="{{ old('allocation', $allocation->allocation) }}" required>
                            @if($errors->has('allocation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('allocation') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.allocation.fields.allocation_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="head_id">{{ trans('cruds.allocation.fields.head') }}</label>
                            <select class="form-control select2" name="head_id" id="head_id" required>
                                @foreach($heads as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('head_id') ? old('head_id') : $allocation->head->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('head'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('head') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.allocation.fields.head_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="year_id">{{ trans('cruds.allocation.fields.year') }}</label>
                            <select class="form-control select2" name="year_id" id="year_id" required>
                                @foreach($years as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('year_id') ? old('year_id') : $allocation->year->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('year'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('year') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.allocation.fields.year_helper') }}</span>
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