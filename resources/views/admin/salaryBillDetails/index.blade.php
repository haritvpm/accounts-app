@extends('layouts.admin')
@section('content')


    <?php

    setlocale(LC_MONETARY, 'en_IN');
   
    ?>


<div class="card">
<!-- <div style="margin-top: 10px;" >
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('admin.salary-bill-details.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.salaryBillDetail.title_singular') }}
                        </a>
                    </div>
                </div>
 -->
    <div class="card-header">
        {{ trans('cruds.salaryBillDetail.title_singular') }} {{ trans('global.list') }}
    </div>


    <div class="card-body">
        <div >
            <table class=" table   cell-border datatable datatable-SalaryBillDetail">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                           Month
                        </th>
                        <th>
                                        Seat
                                    </th>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.pay') }}
                        </th>
                        <th>
                            DA
                        </th>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.hra') }}
                        </th>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.other') }}
                        </th>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.ota') }}
                        </th>
                        <th>
                            {{ trans('cruds.salaryBillDetail.fields.year') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salaryBillDetails as $key => $salaryBillDetail)
                        <tr data-entry-id="{{ $salaryBillDetail->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $salaryBillDetail->created_at->format('Y F') ?? '' }}
                            </td>
                            <td  class="text-center">
                                            {{ $salaryBillDetail->created_by->name ?? '' }}
                                        </td>
                            <td>
                                {{ $salaryBillDetail->pay ?? '' }}
                            </td>
                            <td>
                                {{ $salaryBillDetail->da ?? '' }}
                            </td>
                            <td>
                                {{ $salaryBillDetail->hra ?? '' }}
                            </td>
                            <td>
                                {{ $salaryBillDetail->other ?? '' }}
                            </td>
                            <td>
                                {{ $salaryBillDetail->ota ?? '' }}
                            </td>
                            <td>
                                {{ $salaryBillDetail->year->financial_year ?? '' }}
                            </td>
                            <td>
                                @can('salary_bill_detail_show')
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.salary-bill-details.show', $salaryBillDetail->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                              <!--   @can('salary_bill_detail_edit')
                                    <a class="btn btn-sm btn-info" href="{{ route('admin.salary-bill-details.edit', $salaryBillDetail->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('salary_bill_detail_delete')
                                    <form action="{{ route('admin.salary-bill-details.destroy', $salaryBillDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan -->

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
@can('salary_bill_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.salary-bill-details.massDestroy') }}",
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
  let table = $('.datatable-SalaryBillDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection