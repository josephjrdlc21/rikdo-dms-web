@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Account Management</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Account Management</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
@stop

@section('content')
@include('portal._components.notification')
<div class="card">
    <div class="card-header">
        <h4>Search Filter</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="">
            <div class="row">
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_keyword">Keyword</label>
                        <input type="text" id="input_keyword" class="form-control" placeholder="eg. ID Number, Name" name="keyword" value="{{$keyword}}">
                    </div>
                </div>
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_user_status">Status</label>
                        {!! html()->select('status', $statuses, $selected_status, ['id' => "input_user_status"])->class('form-control selectric') !!}
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label for="input_from">From</label>
                        <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" name="start_date" value="{{$start_date}}">                            
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="form-group">
                        <label for="input_to">To</label>
                        <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" name="end_date" value="{{$end_date}}">                            
                    </div>
                </div>
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_user_role">Role</label>
                        {!! html()->select('role', $roles, $selected_role, ['id' => "input_user_role"])->class('form-control selectric') !!}
                    </div>
                </div>
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_user_department">Department</label>
                        {!! html()->select('department', $departments, $selected_department, ['id' => "input_user_department"])->class('form-control selectric') !!}
                    </div>
                </div>
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_user_course">Course</label>
                        {!! html()->select('course', $courses, $selected_course, ['id' => "input_user_course"])->class('form-control selectric') !!}
                    </div>
                </div>
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_user_yearlevel">Yearlevel</label>
                        {!! html()->select('yearlevel', $yearlevels, $selected_yearlevel, ['id' => "input_user_yearlevel"])->class('form-control selectric') !!}
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Apply Filter</button>
            <a href="{{route('portal.users.index')}}" class="btn btn-sm btn-secondary">Reset Filter</a>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h4>Record Data</h4>
        <div class="card-header-action">
            <a href="{{route('portal.users.create')}}" class="btn btn-sm btn-primary" style="border-radius: 0.25rem !important;">Create User</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Registered</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>                         
                    @forelse($record as $index => $user)
                    <tr>
                        <td>
                            {{$user->name}}<br><a href="{{route('portal.users.show', [$user->id])}}">{{$user->user_info->id_number}}</a>
                        </td>
                        <td>{{Helper::capitalize_text($user->user_info->role)}}</td>
                        <td><span class="badge badge-{{Helper::badge_status($user->status)}}">{{Helper::capitalize_text($user->status)}}</span></td>
                        <td>{{$user->user_info->department->dept_code ?? 'N/A'}}</td>
                        <td>{{$user->user_info->course->course_code ?? 'N/A'}}<br><small>{{$user->user_info->yearlevel->yearlevel_name ?? ''}}</small></td>
                        <td>{{$user->created_at->format("m/d/Y")}}<br><small>{{$user->created_at->format("h:i A")}}</small></td>
                        <td>
                            <div class="btn-group mb-2">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="{{route('portal.users.show', [$user->id])}}">View Details</a>
                                    <a class="dropdown-item" href="{{route('portal.users.edit', [$user->id])}}">Edit Details</a>
                                    <a class="dropdown-item delete-record" data-url="{{route('portal.users.delete', [$user->id])}}" type="button" style="cursor: pointer;">Delete User</a>
                                    <a class="dropdown-item reset-password" data-url="{{route('portal.users.update_password', [$user->id])}}" type="button" style="cursor: pointer;">Reset Password</a>
                                    <a class="dropdown-item status-activation" data-url="{{route('portal.users.update_status', [$user->id])}}" data-status="{{$user->status}}" type="button" style="cursor: pointer;">{{$user->status == 'active' ? 'Deactivate Account' : 'Activate Account'}}</a>
                                </div>
                            </div> 
                        </td>
                    </tr>
                    @empty
                    <td colspan="7">
                        <p class="text-center">No record found yet.</p>
                    </td>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($record->total() > 0)
        <div class="m-4">
            <div class="mb-2">Showing <strong>{{$record->firstItem()}}</strong> to <strong>{{$record->lastItem()}}</strong> of <strong>{{$record->total()}}</strong> entries</div>
            <div>{!!$record->appends(request()->query())->render()!!}</div>
        </div>
        @endif
    </div>
</div>
@stop

@section('page-scripts')
<script type="text/javascript">
    $(".delete-record").on('click', function(){
        var url = $(this).data('url');
        
        swal({
            title: "Are you sure you want to delete this?",
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