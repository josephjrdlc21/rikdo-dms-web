@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Archives</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Archives</a></div>
        <div class="breadcrumb-item active"><a href="#">Completed</a></div>
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
                <a href="{{route('portal.archives.completed')}}" class="btn btn-sm btn-secondary" style="border-radius: 0.25rem !important;">Reset Filter</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_keyword">Keyword</label>
                        <input type="text" id="input_keyword" class="form-control" placeholder="eg. Research Title" name="keyword" value="{{$keyword}}">
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
                <div class="col-sm-12 col-lg-3">
                    <div class="form-group">
                        <label for="input_type">Type</label>
                        {!! html()->select('type', $types, $selected_type, ['id' => "input_type"])->class('form-control selectric') !!}
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
                        <th>Title</th>
                        <th>Type / Status</th>
                        <th>Department</th>
                        <th>Course</th>
                        <th>Process By</th>
                        <th>Date Deleted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>                         
                    @forelse($record as $index => $completed)
                    <tr>
                        <td>{{$loop->index + $record->firstItem()}}. {{$completed->title}}</td>
                        <td>
                            {{$completed->research_type->type}} / 
                            <span class="badge badge-{{Helper::completed_badge_status($completed->status)}}">{{Helper::capitalize_text(str_replace('_', ' ', $completed->status))}}</span>
                        </td>
                        <td>{{$completed->department->dept_code ?? 'N/A'}}</td>
                        <td>{{$completed->course->course_code ?? 'N/A'}}<br><small>{{$completed->yearlevel->yearlevel_name ?? ''}}</small></td>
                        <td>{{$completed->processor->name ?? 'Not Yet Processed'}}</td>
                        <td>{{$completed->deleted_at->format("m/d/Y h:i A")}}</td>
                        <td>
                            <div class="btn-group mb-2">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="{{route('portal.archives.show', [$completed->id])}}?type=completed">View Details</a>
                                    <a class="dropdown-item restore-record" data-url="{{route('portal.archives.restore', [$completed->id])}}" data-type="completed" type="button" style="cursor: pointer;">Restore Research</a>
                                    <a class="dropdown-item delete-record" data-url="#" type="button" style="cursor: pointer;">Delete Permanently</a>
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
            title: "Are you sure you want to delete this permanently?",
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

    $(".restore-record").on('click', function(){
        var url = $(this).data('url');
        var type = $(this).data('type');
        
        swal({
            title: "Are you sure you want to restore this research?",
            icon: "info",
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if(result){
                window.location.href = url + '?type=' + type;
            }
        });
    });
</script>
@stop