@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('spark_bill_data_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.spark-bill-datas.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.sparkBillData.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.sparkBillData.title_singular') }} {{ trans('global.list') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-SparkBillData">
                            <thead>
                                <tr>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.net') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.sparkbill') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.employee') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.employee.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_1') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_2') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_3') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_4') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_5') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_6') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_7') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_8') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_9') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_10') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_11') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.sparkBillData.fields.field_12') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sparkBillDatas as $key => $sparkBillData)
                                    <tr data-entry-id="{{ $sparkBillData->id }}">
                                        <td>
                                            {{ $sparkBillData->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->net ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->sparkbill->sparkcode ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->sparkbill->date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->employee->pen ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->employee->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_1 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_2 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_3 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_4 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_5 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_6 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_7 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_8 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_9 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_10 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_11 ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sparkBillData->field_12 ?? '' }}
                                        </td>
                                        <td>
                                            @can('spark_bill_data_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.spark-bill-datas.show', $sparkBillData->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('spark_bill_data_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.spark-bill-datas.edit', $sparkBillData->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('spark_bill_data_delete')
                                                <form action="{{ route('frontend.spark-bill-datas.destroy', $sparkBillData->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('spark_bill_data_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.spark-bill-datas.massDestroy') }}",
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
  let table = $('.datatable-SparkBillData:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection