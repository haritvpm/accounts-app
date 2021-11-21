@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('salary_bill_detail_create')
                <div style="margin-bottom: 10px;" class="row">
                    <div class="col-lg-12">
                        <a class="btn btn-success" href="{{ route('frontend.salary-bill-details.create') }}">
                            {{ trans('global.add') }} {{ trans('cruds.salaryBillDetail.title_singular') }}
                        </a>
                    </div>
                </div>
            @endcan
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.salaryBillDetail.title_singular') }} {{ trans('global.list') }} ( FY: {{ $curyear }} )
                </div>


                    <?php

                    setlocale(LC_MONETARY, 'en_IN');
                    $amount = money_format('%!i', $allocation['pay']);

                    ?>

                   
                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-SalaryBillDetail">
                            <thead>
                                <tr>
                                     <th>
                                        {{ trans('cruds.salaryBillDetail.fields.id') }}
                                    </th>
                                    <th>
                                        Seat
                                    </th>
                                    <th>
                                        {{ trans('cruds.salaryBillDetail.fields.pay') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.salaryBillDetail.fields.da') }}
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
                                        Date
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
                                            {{ $salaryBillDetail->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $salaryBillDetail->created_by->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ money_format('%!i',  $salaryBillDetail->pay) ?? '' }}

                                        </td>
                                        <td>
                                            {{ money_format('%!i',  $salaryBillDetail->da) ?? '' }}
                                        </td>
                                        <td>
                                            {{ money_format('%!i',  $salaryBillDetail->hra) ?? '' }}
                                        </td>
                                        <td>
                                            {{ money_format('%!i', $salaryBillDetail->other) ?? '' }}
                                        </td>
                                        <td>
                                            {{ money_format('%!i', $salaryBillDetail->ota) ?? '' }}
                                        </td>
                                        <td>
                                            {{ $salaryBillDetail->created_at->format('d/m/Y') ?? '' }}
                                        </td>
                                        <td>
                                            @can('salary_bill_detail_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('frontend.salary-bill-details.show', $salaryBillDetail->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @if( auth()->user()->id == $salaryBillDetail->created_by->id)

                                            @can('salary_bill_detail_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('frontend.salary-bill-details.edit', $salaryBillDetail->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('salary_bill_detail_delete')
                                                <form action="{{ route('frontend.salary-bill-details.destroy', $salaryBillDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>


  <div class="card-body">
                    <div class="table-responsive">
                        <table   class=" table table-bordered table-striped">
                            <thead>
                                <tr>
                                     <th>
                                        
                                    </th>
                                   
                                    <th>
                                        {{ trans('cruds.salaryBillDetail.fields.pay') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.salaryBillDetail.fields.da') }}
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
                                   
                                  
                                </tr>
                            </thead>
                            <tbody>

                                
                                    <tr>
                                         <td>
                                            Allocation
                                        </td>
                                       
                                        @foreach($allocation as $key => $item)
                                        <td>
                                            {{ money_format('%!i',  $item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>
                                

                                    <tr>
                                         <td>
                                            Total Used
                                        </td>
                                      @foreach($total as $key => $item)
                                        <td>
                                            {{ money_format('%!i',  $item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>

                                      <tr>
                                         <td>
                                            Balance
                                        </td>
                                      @foreach($balance as $key => $item)
                                        <td>
                                            {{ money_format('%!i',  $item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>

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
/*@can('salary_bill_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.salary-bill-details.massDestroy') }}",
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
@endcan*/

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