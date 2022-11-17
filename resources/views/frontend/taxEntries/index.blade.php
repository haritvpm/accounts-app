@extends('layouts.frontend')
@section('content')
<div class="content">

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('frontend.tax-entries.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.taxEntry.title_singular') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.taxEntry.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-TaxEntry">
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
                                        {{ trans('cruds.taxEntry.fields.status') }}
                                    </th>
                                    <th>
                                        &nbsp;
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
                                        {{ App\Models\TaxEntry::STATUS_SELECT[$taxEntry->status] ?? '' }}
                                    </td>
                                    <td>

                                        <a class="btn btn-xs btn-primary"
                                            href="{{ route('frontend.tax-entries.show', $taxEntry->id) }}">
                                            {{ trans('global.view') }}
                                        </a>



                                        <a class="btn btn-xs btn-info"
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