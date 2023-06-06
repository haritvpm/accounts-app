@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.sparkBill.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.spark-bills.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $sparkBill->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.date') }}
                                    </th>
                                    <td>
                                        {{ $sparkBill->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.acquittance') }}
                                    </th>
                                    <td>
                                        {{ $sparkBill->acquittance }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.sparkcode') }}
                                    </th>
                                    <td>
                                        {{ $sparkBill->sparkcode }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.year') }}
                                    </th>
                                    <td>
                                        {{ $sparkBill->year->financial_year ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.sparkBill.fields.bill_type') }}
                                    </th>
                                    <td>
                                        {{ $sparkBill->bill_type }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.spark-bills.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection