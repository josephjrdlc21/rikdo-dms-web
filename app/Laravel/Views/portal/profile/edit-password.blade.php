@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Profile</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Profile</a></div>
        <div class="breadcrumb-item">Change Password</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-8 col-lg-6">
        @include('portal._components.notification')
        <div class="card">
            <div class="card-header">
                <h4>Change Password</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    <div class="form-group">
                        <label for="input_current_password">Current Password</label>
                        <input type="password" id="input_current_password" class="form-control" placeholder="Current Password" name="current_password">
                        @if($errors->first('current_password'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('current_password')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_password">Password</label>
                        <input type="password" id="input_password" class="form-control" placeholder="Password" name="password">
                        @if($errors->first('password'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('password')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_password_confirmation">Confirm Password</label>
                        <input type="password" id="input_password_confirmation" class="form-control" placeholder="Confirm Password" name="password_confirmation">
                    </div>
                    <a href="{{route('portal.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop