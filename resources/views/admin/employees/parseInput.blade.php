@extends('layouts.admin')

@section('content')

<div class='row'>
    <div class='col-md-12'>
        <div class="card panel-default">
            <div class="card-header">
                Spark Import
            </div>

            <div class="card-body table-responsive">
                <form class="form-horizontal" method="POST" action="{{ route('admin.employees.processSparkImport') }}">
                    {{ csrf_field() }}
                    
                   

                    @if($new_employees)
                            @foreach($new_employees as $line)
                                <tr>
                                    @foreach($line as $key =>  $field)
                                     
                                        <input type="hidden" name="new_emp[{{$loop->parent->index}}][{{$key}}]" value="{{ $field }}" />
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    <table class="table">
                       
                            <tr>
                                <th>No.</th>
                                <th>PEN</th>
                                <th>PAN</th>
                                <th>NAME</th>
                            </tr>
                       
                        @if($new_employees)
                            @foreach($new_employees as $line)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    @foreach($line as $field)
                                        <td>{{ $field }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                        
                    </table>

                    @if($new_employees)
                    <button type="submit" class="btn btn-primary">
                        @lang('global.app_import_data')
                    </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

@endsection