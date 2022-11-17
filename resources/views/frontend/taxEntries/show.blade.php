@extends('layouts.frontend')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.taxEntry.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.tax-entries.index') }}">
                                {{ trans('global.back_to_list') }}
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
                                        {{ trans('cruds.taxEntry.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\TaxEntry::STATUS_SELECT[$taxEntry->status] ?? '' }}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.tax-entries.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.relatedData') }}
                </div>
                <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                    <li role="presentation">
                        <a href="#date_tds" aria-controls="date_tds" role="tab" data-toggle="tab">
                            {{ trans('cruds.td.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="date_tds">
                        @includeIf('frontend.taxEntries.relationships.dateTds', ['tds' => $taxEntry->dateTds])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection