@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Account Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Account Management</a></div>
        <div class="breadcrumb-item">Edit</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-8 col-lg-6">
        @include('portal._components.edit-account-tab')
        @include('portal._components.notification')
        <div class="card">
            <div class="card-header">
                <h4>Credential</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    {!!csrf_field()!!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_id_number">ID Number</label>
                                <input type="text" id="input_id_number" class="form-control" placeholder="ID Number" name="id_number"  value="{{session()->get('credential.id_number') ?? $user->user_info->id_number}}">
                                @if($errors->first('id_number'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('id_number')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_role">Role</label>
                                {!! html()->select('role', $roles, session()->get('credential.role') ?? $user->user_info->role, ['id' => "input_role"])->class('form-control selectric') !!}
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
                                {!! html()->select('department', $departments, $user->user_info->department->id ?? session()->get('credential.department') ?? '', ['id' => "input_department"])->class('form-control selectric') !!}
                                @if($errors->first('department'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('department')}}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_course">Course</label>
                                {!! html()->select('course', $courses, session()->get('credential.course') ?? $user->user_info->course->id, ['id' => "input_course"])->class('form-control selectric') !!}
                                @if($errors->first('course'))
                                <small class="d-block mt-1 text-danger">{{$errors->first('course')}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input_yearlevel">Yearlevel</label>
                        {!! html()->select('yearlevel', $yearlevels, session()->get('credential.yearlevel') ?? $user->user_info->yearlevel->id, ['id' => "input_yearlevel"])->class('form-control selectric') !!}
                        @if($errors->first('yearlevel'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('yearlevel')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.users.cancel')}}" class="btn btn-sm btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-info">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop