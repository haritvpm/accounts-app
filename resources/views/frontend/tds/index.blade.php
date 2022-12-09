@extends('layouts.frontend')
@section('content')
<div class="content">
    <!-- @can('td_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('frontend.tds.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.td.title_singular') }}
                </a>
            </div>
        </div>
    @endcan -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-heading">
                    {{ trans('cruds.td.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="card-body">
                    <div >
                        <table class=" table datatable datatable-Td">
                            <thead>
                                <tr>
                                    <th width="10">

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
                                           
                                                <a class="btn btn-sm btn-light" href="{{ route('frontend.tds.show', $td->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                          
                                                <a class="btn btn-sm btn-secondary" href="{{ route('frontend.tds.edit', $td->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                          

                                                <form action="{{ route('frontend.tds.destroy', $td->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">

                                                </form>
                                        
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
  //let dtButtons = $.extend(true, [], [$.fn.dataTable.defaults.buttons])
  let dtButtons = $.extend(true, [], [])

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-Td:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  });
  
})

</script>
@endsection
