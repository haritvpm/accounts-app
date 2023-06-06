@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('salary_bill_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.spark-bills.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.sparkBill.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.sparkBill.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-SparkBill">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.acquittance') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.sparkcode') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.year') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.bill_type') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sparkBills as $key => $sparkBill)
                                    <tr data-entry-id="{{ $sparkBill->id }}">
                                        <td>
                                            {{ $sparkBill->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBill->date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBill->acquittance ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBill->sparkcode ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBill->year->financial_year ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBill->bill_type ?? '' }}
                                        </td>
                                        <td>
                                            @can('spark_bill_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.spark-bills.show', $sparkBill->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('spark_bill_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.spark-bills.edit', $sparkBill->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('spark_bill_delete')
                                                <form action="{{ route('frontend.spark-bills.destroy', $sparkBill->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('spark_bill_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.spark-bills.massDestroy') }}",
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
  let table = $('.datatable-SparkBill:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection