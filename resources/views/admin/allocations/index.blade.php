@extends('layouts.admin')
@section('content')
@can('allocation_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.allocations.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.allocation.title_singular') }}
            </a>
        </div>
    </div>
@endcan


<?php

setlocale(LC_MONETARY, 'en_IN');

?>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.allocation.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div >
            <table class=" table table-hover datatable datatable-Allocation">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.allocation.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.allocation.fields.pay') }}
                        </th>
                        <th>
                            DA
                        </th>
                        <th>
                            {{ trans('cruds.allocation.fields.hra') }}
                        </th>
                       
                        <th>
                            {{ trans('cruds.allocation.fields.other') }}
                        </th>
                         <th>
                            {{ trans('cruds.allocation.fields.ota') }}
                        </th>
                        <th>
                            {{ trans('cruds.allocation.fields.year') }}
                        </th>
                         <th>
                           Date
                        </th>
                        
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allocations as $key => $allocation)
                        <tr data-entry-id="{{ $allocation->id }}">
                            <td>

                            </td>
                            <td>
                                {{ number_format( $allocation->id )?? '' }}
                            </td>
                            <td>
                                {{ number_format($allocation->pay )?? '' }}
                            </td>
                            <td>
                                {{ number_format($allocation->da )?? '' }}
                            </td>
                            <td>
                                {{ number_format($allocation->hra )?? '' }}
                            </td>
                            
                            <td>
                                {{ number_format($allocation->other )?? '' }}
                            </td>
                            <td>
                                {{ number_format($allocation->ota )?? '' }}
                            </td>
                            <td>
                                {{ $allocation->year->financial_year ?? '' }}
                            </td>
                             <td>
                                {{ $allocation->created_at->format('d/m/Y') ?? '' }}
                            </td>
                            <td>
                                @can('allocation_show')
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.allocations.show', $allocation->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('allocation_edit')
                                    <a class="btn btn-sm btn-info" href="{{ route('admin.allocations.edit', $allocation->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('allocation_delete')
                                    <form action="{{ route('admin.allocations.destroy', $allocation->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('allocation_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.allocations.massDestroy') }}",
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
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Allocation:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
