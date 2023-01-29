<div class="content">
    
       <!-- This is OK, but then we need to provide edit,delete option as well. there is edit route, but too lazy to paste that to each row
       Let them add only through pdf
          <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('frontend.tds.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.td.title_singular') }}
                </a>
            </div>
        </div> -->
    
    <div class="row">
        <div class="col-lg-12">

            <div class="card ">
               <!--  <div class="card-heading">
                    {{ trans('cruds.td.title_singular') }} {{ trans('global.list') }}
                </div> -->
                <div class="card-body">

                    <div >
                        <table class=" table table-sm datatable datatable-dateTds">
                            <thead>
                                <tr>
                                    
                                    <th>
                                        Sl.No.
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
                                       Updated
                                    </th>
                                    <th>
                                       Remarks
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tds as $key => $td)
                                    <tr data-entry-id="{{ $td->id }}">
                                       
                                        <td>
                                            {{  $loop->iteration ?? '' }}
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
                                            {{ number_format($td->gross) ?? '' }}
                                        </td>
                                        <td>
                                            {{ number_format($td->tds) ?? '' }}
                                        </td>
                                        <td>
                                            {{ $td->date->date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $td->updated_at->format('y-m-d H:i') }}
                                                                                        
                                         </td>
                                        <td>
                                        {{ $td->remarks ?? '' }}
                                        </td> 
                                        <td>
                                          
                                                <!-- <a class="btn btn-sm btn-primary" href="{{ route('frontend.tds.show', $td->id) }}">
                                                    {{ trans('global.view') }}
                                                </a> -->
                                                <!-- edit if this is a manual entry which does not have sparkcode. no edit if parsed from pdf -->
                                                @if(  $td->date->created_by_id == auth()->id() && empty($td->date->sparkcode))
                                                <a class="btn btn-sm btn-info" href="{{ route('frontend.tds.edit', $td->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                                @endif
                                               
<!--                                       
                                                <form action="{{ route('frontend.tds.destroy', $td->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                                                </form> -->
                                   
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
    order: [[ 7, 'desc' ]],
    pageLength: 25,
  });

 


  let table = $('.datatable-dateTds:not(.ajaxTable)').DataTable({ buttons: dtButtons, select: false  })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
