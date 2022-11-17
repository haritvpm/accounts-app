@extends('layouts.admin')
@section('content')
<div class="content">
    @can('td_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.tds.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.td.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
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
                                        &nbsp;
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
                                            @can('td_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.tds.show', $td->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('td_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.tds.edit', $td->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('td_delete')
                                                <form action="{{ route('admin.tds.destroy', $td->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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



        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('td_delete')
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
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Td:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
