@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>User Applications</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Applications</a></div>
        <div class="breadcrumb-item active"><a href="#">Rejected</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
@stop

@section('content')
@include('portal._components.notification')
<div class="card">
    <form method="GET" action="">
        <div class="card-header">
            <h4>Search Filter</h4>
            <div class="card-header-action">
                <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 0.25rem !important;">Apply Filter</button>
                <a href="{{route('portal.users_kyc.rejected')}}" class="btn btn-sm btn-secondary" style="border-radius: 0.25rem !important;">Reset Filter</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_keyword">Keyword</label>
                        <input type="text" id="input_keyword" class="form-control" placeholder="eg. ID Number, Name" name="keyword" value="{{$keyword}}">
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
                        <label for="input_from">From</label>
                        <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" name="start_date" value="{{$start_date}}">                            
                    </div>
                </div>
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_to">To</label>
                        <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" name="end_date" value="{{$end_date}}">                            
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_user_department">Department</label>
                        {!! html()->select('department', $departments, $selected_department, ['id' => "input_user_department"])->class('form-control selectric') !!}
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_user_course">Course</label>
                        {!! html()->select('course', $courses, $selected_course, ['id' => "input_user_course"])->class('form-control selectric') !!}
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_user_yearlevel">Yearlevel</label>
                        {!! html()->select('yearlevel', $yearlevels, $selected_yearlevel, ['id' => "input_user_yearlevel"])->class('form-control selectric') !!}
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="card">
    <div class="card-header">
        <h4>Record Data</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Process By</th>
                        <th>Process At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>                         
                    @forelse($record as $index => $application)
                    <tr>
                        <td>
                            {{$application->firstname}} {{$application->middlename}} {{$application->lastname}} {{$application->suffix}}<br>
                            @if($auth->canAny(['portal.users_kyc.view'], 'web'))         
                            <a href="{{route('portal.users_kyc.show', [$application->id])}}">{{$application->id_number}}</a>
                            @else
                            <a href="#">{{$application->id_number}}</a>
                            @endif
                        </td>
                        <td>{{Helper::capitalize_text($application->role)}}</td>
                        <td>{{$application->department->dept_code ?? 'N/A'}}</td>
                        <td>{{$application->course->course_code ?? 'N/A'}}<br><small>{{$user->yearlevel->yearlevel_name ?? ''}}</small></td>
                        <td>{{$application->processor->name ?? 'N/A'}}</td>
                        <td>{{Carbon::parse($application->process_at)->format("m/d/Y h:i A")}}</td>
                        <td>
                            <div class="btn-group mb-2">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    @if($auth->canAny(['portal.users_kyc.view'], 'web'))         
                                    <a class="dropdown-item" href="{{route('portal.users_kyc.show', [$application->id])}}">View Details</a>
                                    @endif
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