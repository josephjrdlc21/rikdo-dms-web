@extends('portal._layouts.auth')

@section('content')
<div class="row">
    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
        <div class="login-brand">
            <img src="{{asset('assets/img/rikdo-logo.png')}}" alt="logo" width="100" class="shadow rounded-circle">
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h4>Reset Password</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    @include('portal._components.notification')
                    <div class="form-group">
                        <label for="input_password">New Password</label>
                        <input id="input_password" type="password" class="form-control" name="password">
                        @if($errors->first('password'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('password')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_password_confirmation">Confirm Password</label>
                        <input id="input_password_confirmation" type="password" class="form-control" name="password_confirmation">
                        @if($errors->first('password_confirmation'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('password_confirmation')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-5 text-muted text-center">
            Already have an account? 
            <a href="{{route('portal.auth.login')}}">Login</a>
        </div>
        <div class="simple-footer">
            Copyright &copy; {{env('APP_NAME')}} <script>document.write(new Date().getFullYear());</script>
        </div>
    </div>
</div>
@stop