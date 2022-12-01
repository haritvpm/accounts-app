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

                            
                            <a class="btn btn-secondary"
                                href="{{ route('frontend.tax-entries.edit', $taxEntry->id) }}">
                                {{ trans('global.edit') }}
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
                                        Acquittance
                                    </th>
                                    <td>
                                        {{ $taxEntry->acquittance }}
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
                                        Total
                                    </th>
                                    <td>
                                        {{ $total }}
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
                       <!--  <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.tax-entries.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div> -->
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                
                @includeIf('frontend.taxEntries.relationships.dateTds', ['tds' => $taxEntry->dateTds])
               
            </div>

        </div>
    </div>
</div>
@endsection