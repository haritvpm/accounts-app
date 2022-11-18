@extends('layouts.admin')
@section('content')
<div class="content">
  
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.tds.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.td.title_singular') }}
                </a>
            </div>
            <div class="panel-body">
                    <form method="POST" action="{{ route("admin.tds.download") }}" enctype="multipart/form-data">
                    {{ method_field('POST') }}
                        {{csrf_field()}}
                        <div class="form-group {{ $errors->has('year') ? 'has-error' : '' }}">
                            <label class="required" for="year">{{ trans('cruds.tdsReport.fields.year') }}</label>
                            <input class="form-control" type="number" name="year" id="year" value="{{ old('year', '2022') }}" step="1" required>
                            @if($errors->has('year'))
                                <span class="help-block" role="alert">{{ $errors->first('year') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.tdsReport.fields.year_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('period') ? 'has-error' : '' }}">
                            <label class="required">{{ trans('cruds.tdsReport.fields.period') }}</label>
                            @foreach(App\Models\TdsReport::PERIOD_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="period_{{ $key }}" name="period" value="{{ $key }}" {{ old('period', '0') === (string) $key ? 'checked' : '' }} required>
                                    <label for="period_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('period'))
                                <span class="help-block" role="alert">{{ $errors->first('period') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.tdsReport.fields.period_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
        </div>
   
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.td.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Td">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th width="20">
                                        Seat
                                    </th>
                                    <th>
                                        {{ trans('cruds.td.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.td.fields.pan') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.td.fields.pen') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.td.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.td.fields.gross') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.td.fields.tds') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.td.fields.date') }}
                                    </th>
                                    <th>
                                        &nbsp;&nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tds as $key => $td)
                                    <tr data-entry-id="{{ $td->id }}">
                                        <td>

                                        </td>
                                        <td>
                                        {{ $td->created_by->name ?? 'admin' }}
                                        </td>
                                        <td>
                                            {{ $td->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $td->pan ?? '' }}
                                        </td>
                                        <td>
                                            {{ $td->pen ?? '' }}
                                        </td>
                                        <td>
                                            {{ $td->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $td->gross ?? '' }}
                                        </td>
                                        <td>
                                            {{ $td->tds ?? '' }}
                                        </td>
                                        <td>
                                            {{ $td->date->date ?? '' }}
                                        </td>
                                        <td>
                                        @if( empty($td->created_by_id))
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.tds.show', $td->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                          
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.tds.edit', $td->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                          
                                                <form action="{{ route('admin.tds.destroy', $td->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">

                                                    

                                                </form>
                                           @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
  //let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  let dtButtons = $.extend(true, [], [])

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-Td:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection