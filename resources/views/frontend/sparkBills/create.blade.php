@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.sparkBill.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.spark-bills.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="date">{{ trans('cruds.sparkBill.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date') }}" required>
                            @if($errors->has('date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBill.fields.date_helper') }}</span>
                        </div>
                        <div class="custom-file">
                            <input multiple  type="file" name="file" class="form-control custom-file-input" id="chooseFile">
                            <label class="custom-file-label" for="chooseFile">Select file</label>
                        </div>
                       
                        <div class="form-group">
                            <label for="year_id">{{ trans('cruds.sparkBill.fields.year') }}</label>
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
                            <span class="help-block">{{ trans('cruds.sparkBill.fields.year_helper') }}</span>
                        </div>
                       <!--  <div class="form-group">
                            <label for="bill_type">{{ trans('cruds.sparkBill.fields.bill_type') }}</label>
                            <input class="form-control" type="text" name="bill_type" id="bill_type" value="{{ old('bill_type', '') }}">
                            @if($errors->has('bill_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bill_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.sparkBill.fields.bill_type_helper') }}</span>
                        </div> -->
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


@section('scripts')
@parent
<script type="text/javascript">

$('.custom-file input').change(function (e) {
    var files = [];
    for (var i = 0; i < $(this)[0].files.length; i++) {
        files.push($(this)[0].files[i].name);
    }
    $(this).next('.custom-file-label').html(files.join(', '));
});

</script>
@endsection
