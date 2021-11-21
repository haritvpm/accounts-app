@extends('layouts.app')
@section('content')
<div class="login-box">
    <div class="login-logo">
        <div class="login-logo">
            <a href="{{ route('admin.home') }}">
                {{ trans('panel.site_title') }}
            </a>
        </div>
    </div>


    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">
                {{ trans('global.login') }}
            </p>

            @if(session()->has('message'))
                <p class="alert alert-info">
                    {{ session()->get('message') }}
                </p>
            @endif

            <form action="{{ route('login') }}" method="POST">
                {{ csrf_field() }}

                <div class="form-group">
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" name="name" value="{{ old('name', null) }}">

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
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">{{ trans('global.remember_me') }}</label>
                        </div>
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


            @if(Route::has('password.request'))
                <p class="mb-1">
                    <a href="{{ route('password.request') }}">
                        {{ trans('global.forgot_password') }}
                    </a>
                </p>
            @endif
            <p class="mb-1">

            </p>
        </div>
        <!-- /.login-card-body -->
    </div>




</div>



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
                                        {{ trans('cruds.salaryBillDetail.fields.da') }}
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
                                            {{ money_format('%!i',  $item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>
                                

                                    <tr>
                                         <td>
                                            Total Used
                                        </td>
                                      @foreach($total as $key => $item)
                                        <td>
                                            {{ money_format('%!i',  $item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>

                                      <tr>
                                         <td>
                                            Balance
                                        </td>
                                      @foreach($balance as $key => $item)
                                        <td>
                                            {{ money_format('%!i',  $item) ?? '' }}

                                        </td>
                                       
                                     @endforeach

                                    </tr>

                            </tbody>
                        </table>
                    </div>
                </div>


@endsection