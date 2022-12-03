<div class="modal fade" id="SparkImportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Spark Import</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class='row'>
                    <div class='col-md-12'>

                        <form class="form-horizontal" method="POST" action="{{ route($route, ['model' => $model]) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('pdf_file') ? ' has-error' : '' }}">
                                <label for="pdf_file" class="col-md-4 control-label">Spark PDF</label>

                                <div class="col-md-6">
                                    <input id="pdf_file" type="file" class="form-control-file" name="pdf_file" required>

                                    @if($errors->has('pdf_file'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pdf_file') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Import
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>