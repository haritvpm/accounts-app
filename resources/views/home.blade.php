@extends('layouts.admin')
@section('content')

<?php

setlocale(LC_MONETARY, 'en_IN');

?>


<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    You are logged in as Admin!




                 <div class="card-body">
                    <div class="table-responsive">
                        <table   class=" table table-bordered table-striped">
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


                    <!-- data about pending -->
                    <div class="table-responsive">
                        <table   class=" table table-bordered table-striped">
                            <thead>
                                <tr>
                                     <th>
                                    
                                    </th>
                                   
                                    <th>
                                        SalaryBillEntered
                                    </th>
                                    <th>
                                        TDSUploaded
                                    </th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($months as $key => $item)
                                    <tr>
                                         <td>
                                         {{$key}}
                                        </td>
                                                                              
                                        <td>
                                            {{ $item }}
                                        </td>
                                        <td>
                                            {{ $monthsTDS[$key] }}
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
</div>
@endsection
@section('scripts')
@parent

@endsection