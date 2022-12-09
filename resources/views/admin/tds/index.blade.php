@extends('layouts.admin')
@section('content')
<div class="content">
  
   
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
               
                <div class="card-body ">
                <div class="card-title">
                    {{ trans('cruds.td.title_singular') }} {{ trans('global.list') }}
                </div>
                    <div >
                        <table class=" table  datatable datatable-Td">
                            <thead  class="table-light">
                                <tr>
                                    
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
                                                <a class="btn btn-xs btn-light" href="{{ route('admin.tds.show', $td->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                          
                                                <a class="btn btn-xs btn-secondary" href="{{ route('admin.tds.edit', $td->id) }}">
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
  //let dtButtons = $.extend(true, [],[ $.fn.dataTable.defaults.buttons])
  
   let dtButtons = $.extend(true, [], [{"extend":"copy","className":"btn-default","text":"Copy","exportOptions":{"columns":"thead th:not(.noExport)"}},{"extend":"csv","className":"btn-default","text":"CSV","exportOptions":{"columns":"thead th:not(.noExport)"}},{"extend":"excel","className":"btn-default","text":"Excel","exportOptions":{"columns":"thead th:not(.noExport)"}}/*,{"extend":"pdf","className":"btn-default","text":"PDF","exportOptions":{"columns":"thead th:not(.noExport)"}},{"extend":"print","className":"btn-default","text":"Print","exportOptions":{"columns":":visible"}},{"extend":"colvis","className":"btn-default","text":"Columns","exportOptions":{"columns":":visible"}}*/ ])
/* @can('td_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tds.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan */

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 25,
  });

 


  let table = $('.datatable-Td:not(.ajaxTable)').DataTable({ buttons: dtButtons, select: false  })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
