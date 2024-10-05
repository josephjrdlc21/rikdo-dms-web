@extends('portal._layouts.auth')

@section('content')
<div class="row">
    <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
        <div class="login-brand">
            <img src="{{asset('assets/img/rikdo-logo.png')}}" alt="logo" width="100" class="shadow rounded-circle">
        </div>
        @include('portal._components.register-tab')
        <div class="hero align-items-center bg-success text-white">
            <div class="hero-inner text-center">
                <h2>Successfully Registered!</h2>
                <p class="lead">Your registration has been successfully completed. Please wait while an administrator reviews and verifies your account. You will be notified once the verification process is complete. Thank you for your patience.</p>
                <div class="mt-4">
                    <a href="{{route('portal.auth.cancel')}}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-sign-in-alt"></i> Done</a>
                </div>
            </div>
        </div>
        <div class="mt-5 text-muted text-center">
            Already have an account? 
            <a href="{{route('portal.auth.cancel')}}">Login</a>
        </div>
        <div class="simple-footer">
            Copyright &copy; RIKDO <script>document.write(new Date().getFullYear());</script>
        </div>
    </div>
</div>
@stop