@extends('layouts.admin')
@section('content')
@can('allocation_nu_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.allocation-nus.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.allocationNu.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'AllocationNu', 'route' => 'admin.allocation-nus.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.allocationNu.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-AllocationNu">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.allocationNu.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.allocationNu.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.allocationNu.fields.year') }}
                        </th>
                        <th>
                            {{ trans('cruds.allocationNu.fields.head') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allocationNus as $key => $allocationNu)
                        <tr data-entry-id="{{ $allocationNu->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $allocationNu->id ?? '' }}
                            </td>
                            <td>
                                {{ $allocationNu->amount ?? '' }}
                            </td>
                            <td>
                                {{ $allocationNu->year->financial_year ?? '' }}
                            </td>
                            <td>
                                {{ $allocationNu->head->object_head ?? '' }}
                            </td>
                            <td>
                                @can('allocation_nu_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.allocation-nus.show', $allocationNu->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('allocation_nu_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.allocation-nus.edit', $allocationNu->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('allocation_nu_delete')
                                    <form action="{{ route('admin.allocation-nus.destroy', $allocationNu->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
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
@can('allocation_nu_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.allocation-nus.massDestroy') }}",
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
  let table = $('.datatable-AllocationNu:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection