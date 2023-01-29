@extends('layouts.frontend')
@section('content')
<div class="content">

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('frontend.tax-entries.create-311') }}">
                Upload Salary Bill and 311
            </a>
        
            <a class="btn btn-dark" href="{{ route('frontend.tax-entries.create') }}">
                Upload Bill
            </a>

          
            <a class="btn btn-dark" href="{{ route('frontend.tds.create') }}">
                {{ trans('global.add') }} Entry
            </a>
     


        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
               <!--  <div class="card-heading">
                    {{ trans('cruds.taxEntry.title_singular') }} {{ trans('global.list') }}
                </div> -->
                <div class="card-body">
                    <div >
                        <table class=" table  datatable datatable-TaxEntry">
                            <thead>
                                <tr>
                                    
                                    <th>
                                        {{ trans('cruds.taxEntry.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.taxEntry.fields.date') }}
                                    </th>
                                    <th>
                                        Title
                                    </th>
                                    <th>
                                        Sparkcode
                                    </th>
                                    <th>
                                        Items
                                    </th>
                                    <th>
                                        Total Amount
                                    </th>
                                    <th>
                                        Total TDS
                                    </th>
                                    <th>
                                        Updated At
                                    </th>
                                    <th>
                                        &nbsp;&nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($taxEntries as $key => $taxEntry)
                                <tr data-entry-id="{{ $taxEntry->id }}">
                                   
                                    <td>
                                        {{ $taxEntry->id ?? '' }} 
                                    </td>
                                    <td>
                                        {{ $taxEntry->date ?? '' }}
                                    </td>
                                    <td>
                                        <small>{{ $taxEntry->acquittance ??  $taxEntry->dateTds()?->first()?->name }} </small>
                                    </td>
                                    <td>
                                    {{ $taxEntry->sparkcode ?? '' }}
                                    </td>
                                    <td>
                                        {{ $taxEntry->dateTds()->count() }}
                                    </td>
                                    <td> {{ number_format($taxEntry->date_tds_sum_gross)}} </td>
                                    <td>
                                        {{ number_format($taxEntry->date_tds_sum_tds)}}
                                        <!-- {{ App\Models\TaxEntry::STATUS_SELECT[$taxEntry->status] ?? '' }} -->
                                    </td>
                                    <td>
                                            {{ $taxEntry->updated_at->format('y-m-d H:i') }}
                                                                                        
                                         </td>
                                    <td>

                                        <a class="btn btn-sm btn-secondary"
                                            href="{{ route('frontend.tax-entries.show', $taxEntry->id) }}">
                                            {{ trans('global.view') }}
                                        </a>



                                        <!-- <a class="btn btn-sm btn-light"
                                            href="{{ route('frontend.tax-entries.edit', $taxEntry->id) }}">
                                            {{ trans('global.edit') }}
                                        </a> -->



                                        <form action="{{ route('frontend.tax-entries.destroy', $taxEntry->id) }}"
                                            method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-sm btn-danger"
                                                value="{{ trans('global.delete') }}">
                                        </form>


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
  let dtButtons = $.extend(true, [], [])
  //let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    lengthChange:false,
    order: [[ 7, 'desc' ]],
    pageLength: 10,
  
  });
  let table = $('.datatable-TaxEntry:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection