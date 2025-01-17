@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Profile</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Profile</a></div>
        <div class="breadcrumb-item">Details</div>
    </div>
</div>
@stop

@section('content')
@include('portal._components.notification')
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h4>Picture</h4>
            </div>
            <div class="card-body">
                <div class="p-2">
                    <img alt="image" height="180" width="180" src="{{$auth->user_info->directory && $auth->user_info->filename ? "{$auth->user_info->directory}/{$auth->user_info->filename}" : asset('assets/img/avatar/avatar-1.png')}}" class="img-fluid mx-auto d-block rounded-circle">
                </div>
                <form method="POST" action="" enctype="multipart/form-data">
                    {!!csrf_field()!!}
                    <div class="form-group">
                        <label for="input_profile_picture">Update Picture</label>
                        <input type="file" id="input_profile_picture" class="form-control" name="profile_picture" value="{{old('profile_picture')}}">
                        @if($errors->first('profile_picture'))
                        <small class="d-block mt-1 text-danger">{{$errors->first('profile_picture')}}</small>
                        @endif
                    </div>
                    <a href="{{route('portal.index')}}" class="btn btn-block btn-light">Close</a>
                    <button type="submit" class="btn btn-block btn-primary mb-4">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h4>Details</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_number">ID number</label>
                            <p>{{$auth->user_info->id_number ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <p>{{Helper::capitalize_text($auth->user_info->role) ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <p>{{$auth->user_info->email ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_name">Firstname</label>
                            <p>{{$auth->user_info->firstname ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lastname">Lastname</label>
                            <p>{{$auth->user_info->lastname ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="middlename">Middlename</label>
                            <p>{{$auth->user_info->middlename ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="suffix">Suffix</label>
                            <p>{{$auth->user_info->suffix ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="birthdate">Birthdate</label>
                            <p>{{$auth->user_info->birthdate ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact">Contact No.</label>
                            <p>{{$auth->user_info->contact_number ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <p>{{$auth->user_info->department->dept_name ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="course">Course</label>
                            <p>{{$auth->user_info->course->course_name ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="yearlevel">Yearlevel</label>
                            <p>{{$auth->user_info->yearlevel->yearlevel_name ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <p>{{$auth->user_info->address ?? 'N/A'}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop