@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Account Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Account Management</a></div>
        <div class="breadcrumb-item">Account Created</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-9 col-lg-7">
        @include('portal._components.create-account-tab')
        @include('portal._components.notification')
        <div class="hero align-items-center bg-success text-white">
            <div class="hero-inner text-center">
                <h2>Account Created!</h2>
                <p class="lead">You have successfully created an account. Default password was sent to the email of this account.</p>
                <div class="mt-4">
                    <a href="{{route('portal.users.success')}}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-sign-in-alt"></i> Check Users</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop