@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Account Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Account Management</a></div>
        <div class="breadcrumb-item">Information</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>User Information</h4>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <h6><b>ID Number</b></h6>
                            <p>{{$user->user_info->id_number ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Firstname</b></h6>
                            <p>{{$user->user_info->firstname ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Middlename</b></h6>
                            <p>{{$user->user_info->middlename ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Lastname</b></h6>
                            <p>{{$user->user_info->lastname ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Suffix</b></h6>
                            <p>{{$user->user_info->suffix ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Department</b></h6>
                            <p>{{$user->user_info->department->dept_code ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Course</b></h6>
                            <p>{{$user->user_info->course->course_code ?: 'N/A'}} - {{$user->user_info->yearlevel->yearlevel_name ?: 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <h6><b>Role</b></h6>
                            <p>{{Helper::capitalize_text($user->user_info->role) ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Status</b></h6>
                            <p><span class="badge badge-{{Helper::badge_status($user->status)}}">{{$user->status ?: 'N/A'}}</span></p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Birthdate</b></h6>
                            <p>{{$user->user_info->birthdate ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Contact</b></h6>
                            <p>{{$user->user_info->contact_number ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Email</b></h6>
                            <p>{{$user->user_info->email ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Address</b></h6>
                            <p>{{$user->user_info->address ?: 'N/A'}}</p>
                        </div>
                    </div>
                </div>
                <a href="{{route('portal.users.index')}}" class="btn btn-sm btn-danger">Cancel</a>
                <a href="{{route('portal.users.edit', [$user->id])}}" class="btn btn-sm btn-info">Edit User</a>
                <a class="btn btn-sm btn-warning text-white reset-password" data-url="{{route('portal.users.edit_password', [$user->id])}}" type="button" style="cursor: pointer;">Reset Password</a>
                <a class="btn btn-sm btn-secondary text-white status-activation" data-url="{{route('portal.users.edit_status', [$user->id])}}" data-status="{{$user->status}}" type="button" style="cursor: pointer;">{{$user->status == 'active' ? 'Deactivate Account' : 'Activate Account'}}</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('page-scripts')
<script type="text/javascript">
    $(".reset-password").on('click', function(){
        var url = $(this).data('url');
        
        swal({
            title: "Are you sure you want to reset this user password?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if(result){
                window.location.href = url;
            }
        });
    });

    $(".status-activation").on('click', function(){
        var url = $(this).data('url');
        var status = $(this).data('status');
        
        swal({
            title: status === 'active' ? 'Are you sure you want to deactivate this account?' : 'Are you sure you want to activate this account?',
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if(result){
                window.location.href = url;
            }
        });
    });
</script>
@stop