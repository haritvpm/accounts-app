@extends('layouts.admin')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.columns.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.column.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Column', 'route' => 'admin.columns.parseCsvImport'])
        </div>
    </div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.column.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Column">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.column.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.column.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.column.fields.head') }}
                        </th>
                        <th>
                            {{ trans('cruds.column.fields.spark_title') }}
                        </th>
                        <th>
                            {{ trans('cruds.column.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.column.fields.fieldmapping') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($columns as $key => $column)
                        <tr data-entry-id="{{ $column->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $column->id ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Column::TYPE_SELECT[$column->type] ?? '' }}
                            </td>
                            <td>
                                {{ $column->head->object_head ?? '' }}
                            </td>
                            <td>
                                {{ $column->spark_title ?? '' }}
                            </td>
                            <td>
                                {{ $column->title ?? '' }}
                            </td>
                            <td>
                                {{ $column->fieldmapping ?? '' }}
                            </td>
                            <td>

                                @can('column_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.columns.edit', $column->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
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
  
  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Column:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection