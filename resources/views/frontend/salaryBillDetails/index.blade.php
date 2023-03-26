@extends('layouts.frontend')
@section('content')

<style type="text/css">
    
</style>

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
            <!-- <div class="card"> -->
                <div class="card-header">
                    {{ trans('cruds.salaryBillDetail.title_singular') }} {{ trans('global.list') }} ( FY: {{ $curyear }} )
                </div>


                    <?php

                    setlocale(LC_MONETARY, 'en_IN');
                   
                    ?>

                   
                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped datatable datatable-SalaryBillDetail">
                            <thead>
                                <tr>
                                     <th class="noExport">
                                        {{ trans('cruds.salaryBillDetail.fields.id') }}
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
                                        Date
                                    </th>
				     <th>
                                        Bill Total
                                    </th>
                                    <th class="noExport">
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salaryBillDetails as $key => $salaryBillDetail)
                                    <tr data-entry-id="{{ $salaryBillDetail->id }}">
                                         <td  class="text-center">
                                            {{ $salaryBillDetail->id ?? '' }}
                                        </td>
                                        <td  class="text-center">
                                            {{ $salaryBillDetail->created_by->name ?? '' }}
                                        </td>
                                        <td class="text-right" >
                                            {{ number_format( $salaryBillDetail->pay) ?? '' }}
                                        </td>
                                        <td class="text-right" >
                                            {{ number_format( $salaryBillDetail->da) ?? '' }}
                                        </td>
                                        <td class="text-right" >
                                            {{ number_format( $salaryBillDetail->hra) ?? '' }}
                                        </td>
                                        <td class="text-right" >
                                            {{ number_format($salaryBillDetail->other) ?? '' }}
                                        </td>
                                        <td class="text-right" >
                                            {{ number_format($salaryBillDetail->ota) ?? '' }}
                                        </td>
                                        <td>
                                            {{ $salaryBillDetail->created_at->format('d/m/Y') ?? '' }}
                                        </td>
					  <td>
                                            {{ number_format($salaryBillDetail->ota+$salaryBillDetail->hra+$salaryBillDetail->pay+$salaryBillDetail->da+$salaryBillDetail->other) ?? '' }}
                                        </td>
                                        <td>
                                          <!--   @can('salary_bill_detail_show')
                                                <a class="btn btn-sm btn-primary" href="{{ route('frontend.salary-bill-details.show', $salaryBillDetail->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan -->

                                            @if( auth()->user()->id == $salaryBillDetail->created_by->id)

                                            @can('salary_bill_detail_edit')

                                                <a class="btn btn-sm btn-info" href="{{ route('frontend.salary-bill-details.edit', $salaryBillDetail->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('salary_bill_detail_delete')
                                                <form action="{{ route('frontend.salary-bill-details.destroy', $salaryBillDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                  <td class="text-center">Total</td>
                                  <td class="text-center"> </td>

                                  <td class="text-right" >{{ number_format( $totaluser['pay'])  ?? ''}}</td>
                                  <td class="text-right" >{{ number_format( $totaluser['da'])  ?? ''}}</td>
                                  <td class="text-right" >{{ number_format( $totaluser['hra']) ?? '' }}</td>
                                  <td class="text-right" >{{ number_format( $totaluser['other']) ?? '' }}</td>
                                  <td class="text-right" >{{ number_format( $totaluser['ota']) ?? '' }}</td>
                                  <td class="text-right" ></td>
                                  <td class="text-right" ></td>
                                  <td class="text-right" ></td>

                                </tr>
                              </tfoot>
                        </table>
                    </div>
                </div>


 

            <!-- </div> -->

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
    order: [[ 4, 'desc' ]],
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