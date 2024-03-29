@extends('layouts.admin')
@section('content')

<?php

setlocale(LC_MONETARY, 'en_IN');

?>


<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div >
                <div class="card-title">
                    Dashboard
                </div>

                <div >
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    <!-- You are logged in as Admin! -->




                 <div class="card-body">
                    <div>
                        <table   class=" table table-bordered table-secondary">
                            <thead>
                                <tr>
                                     <th>
                                        
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
                                   
                                  
                                </tr>
                            </thead>
                            <tbody>

                                
                                    <tr>
                                         <td>
                                            Allocation {{$curyear}}
                                        </td>
                                       
                                        @foreach($allocation as $key => $item)
                                        <td>
                                            {{ number_format($item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>
                                

                                    <tr>
                                         <td>
                                            Expenditure
                                        </td>
                                      @foreach($total as $key => $item)
                                        <td>
                                            {{ number_format( $item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>

                                      <tr>
                                         <td>
                                            Balance
                                        </td>
                                      @foreach($balance as $key => $item)
                                        <td>
                                            {{ number_format( $item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>

                            </tbody>
                        </table>
                    </div>


                

                </div>



                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection