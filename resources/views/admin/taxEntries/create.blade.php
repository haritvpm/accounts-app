@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <!-- <div class="card-heading">
                    {{ trans('global.create') }} {{ trans('cruds.taxEntry.title_singular') }}
                </div> -->
                <div class="card-body">
                    <form method="POST" action="{{ route( 'admin.tax-entries.store') }}"
                        enctype="multipart/form-data">
                        {{ method_field('POST') }}
                        {{csrf_field()}}
                     
                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                            <label class="required" for="date">{{ trans('cruds.taxEntry.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date') }}"
                                required>
                            @if($errors->has('date'))
                            <span class="help-block" role="alert">{{ $errors->first('date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.taxEntry.fields.status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value disabled {{ old('status', null)===null ? 'selected' : '' }}>{{
                                    trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\TaxEntry::STATUS_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('status', 'draft' )===(string) $key ? 'selected' : ''
                                    }}>{{
                                    $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                            <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('innerfile') ? 'has-error' : '' }}">
                            <label class="required" for="innerfile">{{
                                trans('cruds.taxEntry.fields.innerfile')}}</label>
                            <input type="file" class="form-control-file" name="file1" id="innerfile">
                            @if($errors->has('innerfile'))
                            <span class="help-block" role="alert">{{ $errors->first('innerfile') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.innerfile_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('deductionfile') ? 'has-error' : '' }}">
                            <label class="required" for="deductionfile">{{
                                trans('cruds.taxEntry.fields.deductionfile')
                                }}</label>
                            <input type="file" class="form-control-file" name="file2" id="deductionfile">
                            @if($errors->has('deductionfile'))
                            <span class="help-block" role="alert">{{ $errors->first('deductionfile') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.deductionfile_helper')
                                }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection

