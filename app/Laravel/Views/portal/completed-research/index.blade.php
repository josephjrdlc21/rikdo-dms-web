@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Completed Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Completed Research</a></div>
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
                <a href="{{route('portal.completed_research.index')}}" class="btn btn-sm btn-secondary" style="border-radius: 0.25rem !important;">Reset Filter</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_keyword">Keyword</label>
                        <input type="text" id="input_keyword" class="form-control" placeholder="eg. Research Title, Authors" name="keyword" value="{{$keyword}}">
                    </div>
                </div>
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_status">Status</label>
                        {!! html()->select('status', $statuses, $selected_status, ['id' => "input_status"])->class('form-control selectric') !!}
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
        <div class="card-header-action">
            <a href="#" class="btn btn-sm btn-success" style="border-radius: 0.25rem !important;">Submit Research</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th class="text-center">Title</th>
                        <th>Status</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Process By</th>
                        <th>Date Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>                         
                    @forelse($record as $index => $completed)
                    <tr>
                        <td>{{$loop->index + $record->firstItem()}}</td>
                        <td class="text-center">{{$completed->title}}<br><small>{{$completed->research_type->type}}</small></td>
                        <td><span class="badge badge-{{Helper::completed_badge_status($completed->status)}}">{{Helper::capitalize_text($completed->status)}}</span></td>
                        <td>{{$completed->department->dept_code ?? 'N/A'}}</td>
                        <td>{{$completed->course->course_code ?? 'N/A'}}<br><small>{{$completed->yearlevel->yearlevel_name ?? ''}}</small></td>
                        <td>{{$completed->processor->name}}</td>
                        <td>{{$completed->created_at->format("m/d/Y")}}<br><small>{{$completed->created_at->format("h:i A")}}</small></td>
                        <td>
                            <div class="btn-group mb-2">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="#">View Details</a>
                                    <a class="dropdown-item" href="#">Post Research</a>
                                    <a class="dropdown-item" href="#">Delete Research</a>
                                </div>
                            </div> 
                        </td>
                    </tr>
                    @empty
                    <td colspan="8">
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