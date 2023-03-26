@extends('layouts.admin')
@section('content')
<div class="content">
 
<div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <div class="card-body">
                        <form  class="form-inline" method="POST" action="{{ route("admin.tds.download") }}" enctype="multipart/form-data">
                        {{ method_field('POST') }}
                            {{csrf_field()}}
                                                    
                            <div class="input-group">

                                <div class="form-group {{ $errors->has('year') ? 'has-error' : '' }}" style="margin: 5px;" >
                                    <input class="form-control" type="number" name="year" id="year" value="{{ old('year', now()->year ) }}" step="1" required >
                                    @if($errors->has('year'))
                                        <span class="help-block" role="alert">{{ $errors->first('year') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.tdsReport.fields.year_helper') }}</span>
                                </div>

                                <div class="form-group {{ $errors->has('period') ? 'has-error' : '' }}" style="margin: 5px;">
                                    <select class="form-control" name="period" id="period" required>
                                      
                                        @foreach(App\Models\TdsReport::PERIOD_RADIO as $key => $label)
                                            <option value="{{ $key }}" {{ old('period', $period) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('period'))
                                        <span class="help-block" role="alert">{{ $errors->first('period') }}</span>
                                    @endif
                            </div>
                            
                                <div class="form-group" style="margin: 5px;">
                                    <button class="btn btn-success" type="submit">
                                        Download
                                    </button>
                                </div>

                                </div>

                        
                        </form>
                </div>
        </div>
    </div>

    <hr/>
<div style="margin-bottom: 10px;" class="row">

    <div class="col-lg-12">
    
        <a class="btn btn-dark" href="{{ route('admin.tds.create') }}">
            {{ trans('global.add') }} 26Q
        </a>
    </div>
</div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card ">
              <!--   <div class="card-heading">
                    {{ trans('cruds.taxEntry.title_singular') }} {{ trans('global.list') }}
                </div> -->
                <div class="card-body">
                    <div >
                        <table class=" table datatable datatable-TaxEntry">
                            <thead>
                                <tr>
                                    
                                    <th>
                                        {{ trans('cruds.taxEntry.fields.id') }}
                                    </th>
                                    <th>
                                        Seat
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
                                        Last Updated
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
                                    {{ $taxEntry->created_by->name ?? 'Admin' }}
                                    </td>
                                    <td>
                                        {{ $taxEntry->date ?? '' }}
                                    </td>
                                    <td>
                                      <small>  {{ $taxEntry->acquittance ??  $taxEntry->dateTds()?->first()?->name }}  </small>
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
                                            href="{{ route('admin.tax-entries.show', $taxEntry->id) }}">
                                            {{ trans('global.view') }}
                                        </a>


                                        @if( empty($taxEntry->created_by_id) || $taxEntry->created_by_id == auth()->id() )
                                       <!--  <a class="btn btn-sm btn-info"
                                            href="  {{ route('admin.tax-entries.edit', $taxEntry->id) }}"  
                                             >
                                            {{ trans('global.edit') }}
                                        </a> -->
                                        <form action="{{ route('admin.tax-entries.destroy', $taxEntry->id) }}"
                                            method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-sm btn-danger"
                                                value="{{ trans('global.delete') }}">
                                        </form>
                                        @endif

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
    order: [[ 8, 'desc' ]],
    pageLength: 25,
  });
  let table = $('.datatable-TaxEntry:not(.ajaxTable)').DataTable({ buttons: dtButtons, select: false })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
