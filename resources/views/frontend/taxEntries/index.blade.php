@extends('layouts.frontend')
@section('content')
<div class="content">

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-dark" href="{{ route('frontend.tax-entries.create') }}">
                Upload 
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
               <!--  <div class="panel-heading">
                    {{ trans('cruds.taxEntry.title_singular') }} {{ trans('global.list') }}
                </div> -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-hover datatable datatable-TaxEntry">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.taxEntry.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.taxEntry.fields.date') }}
                                    </th>
                                    <th>
                                        Items
                                    </th>
                                    <th>
                                        {{ trans('cruds.taxEntry.fields.status') }}
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

                                    </td>
                                    <td>
                                        {{ $taxEntry->id ?? '' }} 
                                    </td>
                                    <td>
                                        {{ $taxEntry->date ?? '' }}
                                    </td>
                                    <td>
                                        {{ $taxEntry->dateTds()->count() }}
                                    </td>
                                    
                                    <td>
                                        {{ App\Models\TaxEntry::STATUS_SELECT[$taxEntry->status] ?? '' }}
                                    </td>
                                    <td>

                                        <a class="btn btn-xs btn-light"
                                            href="{{ route('frontend.tax-entries.show', $taxEntry->id) }}">
                                            {{ trans('global.view') }}
                                        </a>



                                        <a class="btn btn-xs btn-secondary"
                                            href="{{ route('frontend.tax-entries.edit', $taxEntry->id) }}">
                                            {{ trans('global.edit') }}
                                        </a>



                                        <form action="{{ route('frontend.tax-entries.destroy', $taxEntry->id) }}"
                                            method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger"
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
    order: [[ 1, 'desc' ]],
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