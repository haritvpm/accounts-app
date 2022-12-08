@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
               <!--  <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.taxEntry.title') }}
                </div> -->
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-secondary" href="{{ route('admin.tax-entries.index') }}">
                            <i class="fa-solid fa-chevron-left"></i>  {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.taxEntry.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $taxEntry->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.taxEntry.fields.date') }}
                                    </th>
                                    <td>
                                        {{ $taxEntry->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                    Title
                                    </th>
                                    <td>
                                        {{ $taxEntry->acquittance ?? 'Admin Entry'}}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Sparkcode
                                    </th>
                                    <td>
                                        {{ $taxEntry->sparkcode }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Items
                                    </th>
                                    <td>
                                        {{ $taxEntry->dateTds()->count() }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Total Amount
                                    </th>
                                    <td>
                                        {{ $totalgross }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        Total TDS
                                    </th>
                                    <td>
                                        {{ $totaltds }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.taxEntry.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\TaxEntry::STATUS_SELECT[$taxEntry->status] ?? '' }}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.tax-entries.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                
                @includeIf('admin.taxEntries.relationships.dateTds', ['tds' => $taxEntry->dateTds])
               
            </div>

        </div>
    </div>
</div>
@endsection