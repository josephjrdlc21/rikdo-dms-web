@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Account Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Applications</a></div>
        <div class="breadcrumb-item active"><a href="#">Show</a></div>
        <div class="breadcrumb-item">Details</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Application Details</h4>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <h6><b>ID Number</b></h6>
                            <p>{{$user_kyc->id_number ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Firstname</b></h6>
                            <p>{{$user_kyc->firstname ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Middlename</b></h6>
                            <p>{{$user_kyc->middlename ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Lastname</b></h6>
                            <p>{{$user_kyc->lastname ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Suffix</b></h6>
                            <p>{{$user_kyc->suffix ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Department</b></h6>
                            <p>{{$user_kyc->department->dept_code ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Course</b></h6>
                            <p>{{$user_kyc->course->course_code ?: 'N/A'}} - {{$user_kyc->yearlevel->yearlevel_name ?: 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <h6><b>Role</b></h6>
                            <p>{{Helper::capitalize_text($user_kyc->role) ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Status</b></h6>
                            <p><span class="badge badge-{{Helper::application_badge_status($user_kyc->status)}}">{{Helper::capitalize_text($user_kyc->status) ?: 'N/A'}}</span></p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Birthdate</b></h6>
                            <p>{{$user_kyc->birthdate ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Contact</b></h6>
                            <p>{{$user_kyc->contact_number ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Email</b></h6>
                            <p>{{$user_kyc->email ?: 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Address</b></h6>
                            <p>{{$user_kyc->address ?: 'N/A'}}</p>
                        </div>
                    </div>
                </div>
                <a href="{{route('portal.users_kyc.index')}}" class="btn btn-sm btn-secondary">Close</a>
                @if($user_kyc->status === 'pending')
                <a data-url="{{route('portal.users_kyc.update_status', [$user_kyc->id, 'approved'])}}" class="btn btn-sm btn-success text-white btn-approve" type="button" style="cursor: pointer;">Approve Appplication</a>
                <a data-url="{{route('portal.users_kyc.update_status', [$user_kyc->id, 'rejected'])}}" class="btn btn-sm btn-danger text-white btn-rejected" type="button" style="cursor: pointer;">Reject Appplication</a>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('page-scripts')
<script type="text/javascript">
    $(".btn-approve").on('click', function(){
        var url = $(this).data('url');
        
        swal({
            title: "Are you sure you want approve this user application?",
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

    $(".btn-rejected").on('click', function(){
        var url = $(this).data('url');
        
        swal({
            title: "Are you sure you want reject this user application?",
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