@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="card ">
                
                <div class="card-body">
                    <!-- <h5 class="card-title">
                    {{ trans('global.create') }} {{ trans('cruds.taxEntry.title_singular') }}
                    </h5>
                  -->
                    <form id="fileUploadForm" method="POST" action="{{ route( 'admin.tax-entries.pdf2csv') }}"
                        enctype="multipart/form-data">
                        {{ method_field('POST') }}
                        {{csrf_field()}}
                                          
                        <div class="form-group {{ $errors->has('innerfile') ? 'has-error' : '' }}">
                            <label class="required" for="innerfile">PDF</label>
                            <input type="file" class="form-control-file" name="file1" id="innerfile">
                            @if($errors->has('innerfile'))
                            <span class="help-block" role="alert">{{ $errors->first('innerfile') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.innerfile_helper') }}</span>
                        </div>
                      
                        <hr>
                        <div class="form-group">
                            <a class="btn btn-secondary"  href="{{ route('admin.tax-entries.index') }}">
                            Cancel
                            </a>
                            <button class="btn btn-primary" type="submit">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;" id="spinnerbtn" ></span>

                                Submit
                            </button>
                        </div>
                    </form>

	    <div class="alert alert-danger" id="alert" style="display:none;">
          <ul class="list-unstyled"  id="myerror">
            
          </ul>
        </div>


                </div>
            </div>



        </div>
    </div>
</div>
@endsection
