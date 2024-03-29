@extends('layouts.frontend')
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
                    <form id="fileUploadForm" method="POST" action="{{ route( 'frontend.tax-entries.store') }}"
                        enctype="multipart/form-data">
                        {{ method_field('POST') }}
                        {{csrf_field()}}
                     
                        <!-- <div class="form-group">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                            </div>
                        </div> -->
                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                            <label class="required" for="date">{{ trans('cruds.taxEntry.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date') }}"
                                required>
                            @if($errors->has('date'))
                            <span class="help-block" role="alert">{{ $errors->first('date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.date_helper') }}</span>
                        </div>
                        <!-- <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
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
                        </div> -->
                        <div class="form-group {{ $errors->has('innerfile') ? 'has-error' : '' }}">
                            <label class="required" for="innerfile">{{
                                trans('cruds.taxEntry.fields.innerfile')}}</label>
                            <input type="file" class="form-control-file" name="file1" id="innerfile">
                            @if($errors->has('innerfile'))
                            <span class="help-block" role="alert">{{ $errors->first('innerfile') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.innerfile_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="has_it" value="0">
                                <input type="checkbox" name="has_it" id="has_it" value="1" {{ old('has_it', 0) == 1 || old('has_it') === null ? 'checked' : '' }}>
                                <label for="has_it">{{ trans('cruds.taxEntry.fields.has_it') }}</label>
                            </div>
                            @if($errors->has('has_it'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('has_it') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.has_it_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="tds_rows_only" value="0">
                                <input type="checkbox" name="tds_rows_only" id="tds_rows_only" value="1" {{ old('tds_rows_only', 0) == 1 || old('tds_rows_only') !== null ? 'checked' : '' }}>
                                <label for="tds_rows_only">{{ trans('cruds.taxEntry.fields.tds_rows_only') }}</label>
                            </div>
                            @if($errors->has('tds_rows_only'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tds_rows_only') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.taxEntry.fields.tds_rows_only_helper') }}</span>
                        </div>
                        <hr>
                        <div class="form-group">
                            <a class="btn btn-secondary"  href="{{ route('frontend.tax-entries.index') }}">
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



@section('scripts')
@parent
    
    <!-- <script src="{{ asset('js/cdnjs/jquery.form.min.js') }}"></script> -->
    <script>
        $(function () {
            $(document).ready(function () {

                $('#fileUploadForm').ajaxForm({
                    beforeSend: function () {
                        var percentage = '0';
                    },

                    beforeSubmit: function(arr, $form, options) {
                        // The array of form data takes the following form:
                        // [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ]

                        // return false to cancel submit
                        //alert( JSON.stringify( arr));
			            $('#myerror').empty()
                        let valid = true;
                        for (const item of arr) {
                          if( item?.name == 'file1' || item?.name == 'file2' ) {
                            if( ! item?.value ){

			                    $('#myerror').append('<li>Choose PDF Files</li>');
							
                                valid = false;
                                break;
                            }
                            
                          } 
                          else if( item?.name == 'date' ) {
                            if( ! item?.value ){
				                $('#myerror').append('<li>Fill all fields</li>');
                                valid = false;
                                break;
                            }
                            
                          }  
                        }

                        if(!valid){
                           $('#alert').show();
                        } else {
                            $('#spinnerbtn').show();
                        }

                        return valid;

                        },
                    uploadProgress: function (event, position, total, percentComplete) {
                       // var percentage = percentComplete;
                       // if(  percentage > 90 ){
                       //     percentage = 90
                      //  }
                      //  $('.progress .progress-bar').css("width", percentage+'%', function() {
                       //   return $(this).attr("aria-valuenow", percentage) + "%";
                      //  })
                    },
                    complete: function (xhr) {
                        
                        let jsonResponse = JSON.parse(xhr.responseText);
                        //alert(jsonResponse);
                       
                        
                        //console.log('File has uploaded');
                        if( jsonResponse?.error ){
                            $('#spinnerbtn').hide();
                           // alert(jsonResponse.error);
                           $('#myerror').empty();
                           $('#myerror').append('<li>' + jsonResponse.error + '</li>');
                           $('#alert').show();

                        }
                        else{
                            var i = window.location.href.lastIndexOf("/");
                            window.location = window.location.href.substr(0,i)

                        }
                    }
                });


            });
        });
    </script>
    <script>
       $('#has_it').on('change', function() {
        if (!$('#has_it').is(':checked')) {
                $('#tds_rows_only').attr('disabled', true);
                $('#tds_rows_only').prop('checked', false);
            } else {
                $('#tds_rows_only').attr('disabled', false);
                $('#tds_rows_only').prop('checked', false);
            }
        });
    </script>
@endsection

