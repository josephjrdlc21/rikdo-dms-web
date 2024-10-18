@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>User Applications</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Applications</a></div>
        <div class="breadcrumb-item active"><a href="#">Approved</a></div>
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
            <button type="submit" class="btn btn-sm btn-primary">Apply Filter</button>
            <a href="{{route('portal.users_kyc.approved')}}" class="btn btn-sm btn-secondary">Reset Filter</a>
        </form>
    </div>
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
                        <th>Status</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Process By</th>
                        <th>Process At</th>
                    </tr>
                </thead>
                <tbody>                         
                    @forelse($record as $index => $application)
                    <tr>
                        <td>
                            {{$application->firstname}} {{$application->middlename}} {{$application->lastname}} {{$application->suffix}}<br>
                            <a href="{{route('portal.users_kyc.show', [$application->id])}}">{{$application->id_number}}</a>
                        </td>
                        <td>{{Helper::capitalize_text($application->role)}}</td>
                        <td><span class="badge badge-{{Helper::application_badge_status($application->status)}}">{{Helper::capitalize_text($application->status)}}</span></td>
                        <td>{{$application->department->dept_code ?? 'N/A'}}</td>
                        <td>{{$application->course->course_code ?? 'N/A'}}<br><small>{{$user->yearlevel->yearlevel_name ?? ''}}</small></td>
                        <td>{{$application->processor->name ?? 'N/A'}}</td>
                        <td>{{Carbon::parse($application->process_at)->format("m/d/Y")}}<br><small>{{Carbon::parse($application->process_at)->format("h:i A")}}</small></td>
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