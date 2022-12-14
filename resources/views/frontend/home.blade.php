@extends('layouts.frontend')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- <div class="card"> -->
                <!-- <div class="card-header">{{ __('Dashboard') }}</div> -->

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    <?php

                    setlocale(LC_MONETARY, 'en_IN');
                   
                    ?>
                
                    <div class="table-responsive">
                        <table   class=" table">
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
                                            Allocation
                                        </td>
                                       
                                        @foreach($allocation as $key => $item)
                                        <td>
                                            {{ number_format( $item) ?? '' }}

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
                                            {{ number_format($item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>

                            </tbody>
                        </table>
                    </div>
                





                </div>
            <!-- </div> -->
        </div>
    </div>
</div>
@endsection