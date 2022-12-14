@extends('layouts.admin')
@section('content')
@can('year_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.years.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.year.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.year.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div>
            <table class=" table table-hover datatable datatable-Year">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.year.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.year.fields.financial_year') }}
                        </th>
                        <th>
                            {{ trans('cruds.year.fields.active') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($years as $key => $year)
                        <tr data-entry-id="{{ $year->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $year->id ?? '' }}
                            </td>
                            <td>
                                {{ $year->financial_year ?? '' }}
                            </td>
                            <td>
                                {{ $year->active ? 'Yes' : 'No' }}
                            </td>
                            <td>
                                @can('year_show')
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.years.show', $year->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('year_edit')
                                    <a class="btn btn-sm btn-info" href="{{ route('admin.years.edit', $year->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('year_delete')
                                    <form action="{{ route('admin.years.destroy', $year->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('year_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.years.massDestroy') }}",
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
  let table = $('.datatable-Year:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
