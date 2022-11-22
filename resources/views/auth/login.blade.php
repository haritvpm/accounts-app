@extends('layouts.app')
@section('content')


<style type="text/css">

.login {
  min-height: 100vh;
}

.bg-image {
  background-image: url('images/endless-constellation.svg');

  background-size: cover;
  background-position: center;
   z-index: 1000;
  background-color: #fff;
}

.login-heading {
  font-weight: 300;
}

.btn-login {
  font-size: 0.9rem;
  letter-spacing: 0.05rem;
  padding: 0.75rem 1rem;
}

table tr td, table tr th{
    background-color: rgba(210,130,240, 0.0) !important;
       color: rgba(210,240,240, 0.8) ;

}

</style>


<?php

setlocale(LC_MONETARY, 'en_IN');

?>

<div class="container-fluid ps-md-0">
  <div class="row g-0">

    <div class="d-none  d-md-flex col-md-8 col-lg-8 bg-image align-items-center justify-content-center">
    
    
        <div class="table-responsive-md">

                       <table class="table table-borderless">

                            <thead>
                                <tr>
                                     <th>
                                      {{ $curyear ?? '' }}  
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
                                           <i> Allocation
                                        </td>
                                       
                                        @foreach($allocation as $key => $item)
                                        <td>
                                            {{ number_format($item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>
                                

                                    <tr>
                                         <td>
                                            <i>Expenditure
                                        </td>
                                      @foreach($total as $key => $item)
                                        <td>
                                            {{ number_format(  $item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>

                                      <tr>
                                         <td>
                                            <i>Balance
                                        </td>
                                      @foreach($balance as $key => $item)
                                        <td>
                                           <b>  {{ number_format($item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>

                            </tbody>
                        </table>

      
      </div>

    </div>

    <!-- login -->

    <div class="col-md-4 col-lg-4 bg-white  ">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
              <h3 class="login-heading mb-4">AccountsApp</h3>

               @if(session()->has('message'))
                            <p class="alert alert-info">
                                {{ session()->get('message') }}
                            </p>
                @endif
              <!-- Sign In Form -->
               <form action="{{ route('login') }}" method="POST">
                {{ csrf_field() }}

                <div class="form-group">
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="Seat" name="name" value="{{ old('name', null) }}">

                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                   
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ trans('global.login_password') }}">

                    @if($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>


                <div class="row">
                    <div class="col-8">
                       
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            {{ trans('global.login') }}
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection