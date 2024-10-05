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
                <h4>Register Credential</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    @include('portal._components.notification')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_id_number">ID Number</label>
                                <input type="text" id="input_id_number" class="form-control" placeholder="ID Number" name="id_number"  value="{{old('id_number', session()->get('credential.id_number') ?? '')}}">
                                @if($errors->first('id_number'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('id_number')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_role">Role</label>
                                {!! html()->select('role', $roles, old('role', session()->get('credential.role') ?? ''), ['id' => "input_role"])->class('form-control selectric') !!}
                                @if($errors->first('role'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('role')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <h6>For Student</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_department">Department</label>
                                {!! html()->select('department', $departments, old('department', session()->get('credential.department') ?? ''), ['id' => "input_department"])->class('form-control selectric') !!}
                                @if($errors->first('department'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('department')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_course">Course</label>
                                {!! html()->select('course', $courses, old('course', session()->get('credential.course') ?? ''), ['id' => "input_course"])->class('form-control selectric') !!}
                                @if($errors->first('course'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('course')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_yearlevel">Yearlevel</label>
                        {!! html()->select('yearlevel', $yearlevels, old('yearlevel', session()->get('credential.yearlevel') ?? ''), ['id' => "input_yearlevel"])->class('form-control selectric') !!}
                        @if($errors->first('yearlevel'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('yearlevel')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.auth.cancel')}}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
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