@extends('portal._layouts.auth')

@section('content')
<div class="row">
    <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
        <div class="login-brand">
            <img src="{{asset('assets/img/rikdo-logo.png')}}" alt="logo" width="100" class="shadow rounded-circle">
        </div>
        @include('portal._components.register-tab')
        <div class="card card-primary">
            <div class="card-header">
                <h4>Register Personal Info</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    @include('portal._components.notification')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_firstname">Firstname</label>
                                <input type="text" id="input_firstname" class="form-control" placeholder="Firstname" name="firstname"  value="{{old('firstname', session()->get('personal_info.firstname') ?? '')}}">
                                @if($errors->first('firstname'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('firstname')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_middlename">Middlename</label>
                                <input type="text" id="input_middlename" class="form-control" placeholder="Middlename" name="middlename"  value="{{old('middlename', session()->get('personal_info.middlename') ?? '')}}">
                                @if($errors->first('middlename'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('middlename')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_lastname">Lastname</label>
                                <input type="text" id="input_lastname" class="form-control" placeholder="Lastname" name="lastname"  value="{{old('lastname', session()->get('personal_info.lastname') ?? '')}}">
                                @if($errors->first('lastname'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('lastname')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_suffix">Suffix</label>
                                <input type="text" id="input_suffix" class="form-control" placeholder="Suffix" name="suffix"  value="{{old('suffix', session()->get('personal_info.suffix') ?? '')}}">
                                @if($errors->first('suffix'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('suffix')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_birthdate">Birthdate</label>
                                <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" name="birthdate" value="{{old('birthdate', session()->get('personal_info.birthdate') ?? '')}}">                            
                                @if($errors->first('birthdate'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('birthdate')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_contact">Contact No.</label>
                                <input type="text" id="input_contact" class="form-control" placeholder="+63" name="contact"  value="{{old('contact', session()->get('personal_info.contact') ?? '')}}">
                                @if($errors->first('contact'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('contact')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_email">Email</label>
                        <input type="email" id="input_email" class="form-control" placeholder="Email" name="email"  value="{{old('email', session()->get('personal_info.email') ?? '')}}">
                        @if($errors->first('email'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('email')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_address">Address</label>
                        <input type="text" id="input_address" class="form-control" placeholder="Address" name="address"  value="{{old('address', session()->get('personal_info.address') ?? '')}}">
                        @if($errors->first('address'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('address')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.auth.cancel')}}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-primary">Proceed</button>
                </form>
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