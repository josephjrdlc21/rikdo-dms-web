@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Account Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Account Management</a></div>
        <div class="breadcrumb-item">Create</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-8 col-lg-6">
        @include('portal._components.account-tab')
        @include('portal._components.notification')
        <div class="card">
            <div class="card-header">
                <h4>Personal Information</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_firstname">Firstname</label>
                                <input type="text" id="input_firstname" class="form-control" placeholder="Firstname" name="firstname"  value="{{old('firstname', session()->get('personal_info.firstname') ? session()->get('personal_info.firstname') : '')}}">
                                @if($errors->first('firstname'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('firstname')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_middlename">Middlename</label>
                                <input type="text" id="input_middlename" class="form-control" placeholder="Middlename" name="middlename"  value="{{old('middlename', session()->get('personal_info.middlename') ? session()->get('personal_info.middlename') : '')}}">
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
                                <input type="text" id="input_lastname" class="form-control" placeholder="Lastname" name="lastname"  value="{{old('lastname', session()->get('personal_info.lastname') ? session()->get('personal_info.lastname') : '')}}">
                                @if($errors->first('lastname'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('lastname')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_suffix">Suffix</label>
                                <input type="text" id="input_suffix" class="form-control" placeholder="Suffix" name="suffix"  value="{{old('suffix', session()->get('personal_info.suffix') ? session()->get('personal_info.suffix') : '')}}">
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
                                <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" name="birthdate" value="{{old('birthdate', session()->get('personal_info.birthdate') ? session()->get('personal_info.birthdate') : '')}}">                            
                                @if($errors->first('birthdate'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('birthdate')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_contact">Contact No.</label>
                                <input type="text" id="input_contact" class="form-control" placeholder="+63" name="contact"  value="{{old('contact', session()->get('personal_info.contact') ? session()->get('personal_info.contact') : '')}}">
                                @if($errors->first('contact'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('contact')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_email">Email</label>
                        <input type="email" id="input_email" class="form-control" placeholder="Email" name="email"  value="{{old('email', session()->get('personal_info.email') ? session()->get('personal_info.email') : '')}}">
                        @if($errors->first('email'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('email')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="input_address">Address</label>
                        <input type="text" id="input_address" class="form-control" placeholder="Address" name="address"  value="{{old('address', session()->get('personal_info.address') ? session()->get('personal_info.address') : '')}}">
                        @if($errors->first('address'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('address')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.users.cancel')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Proceed</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('page-scripts')
<script type="text/javascript">
   $(function() {
     
    });
</script>
@stop