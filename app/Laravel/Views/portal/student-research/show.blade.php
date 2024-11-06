@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Student Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Student Research</a></div>
        <div class="breadcrumb-item active"><a href="#">Show</a></div>
        <div class="breadcrumb-item">Details</div>
    </div>
</div>
@stop

@section('content')
@include('portal._components.notification')
<div class="row">
    <div class="col-sm-12 col-md-7 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Research Details</h4>
                <div class="card-header-action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false" style="border-radius: 0.25rem !important;">Options</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 26px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a href="{{route('portal.student_research.download', [$research->id])}}" class="dropdown-item">Download File</a>
                            <a href="{{route('portal.student_research.edit_share', [$research->id])}}" class="dropdown-item">Share Research</a>
                        </div>
                    </div>
                    @if($research->status == "pending")
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false" style="border-radius: 0.25rem !important;">Remarks</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 26px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a data-url="{{route('portal.student_research.edit_status', ['id' => $research->id, 'status' => "approved"])}}" class="dropdown-item status-approve" type="button" style="cursor: pointer;">Approve</a>
                            <a href="{{route('portal.student_research.edit_status', ['id' => $research->id, 'status' => "for_revision"])}}" class="dropdown-item">For Revision</a>
                            <a data-url="{{route('portal.student_research.edit_status', ['id' => $research->id, 'status' => "rejected"])}}" class="dropdown-item status-reject" type="button" style="cursor: pointer;">Reject</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <h6><b>Title</b></h6>
                        <p>{{$research->title ?? 'N/A'}}</p>
                    </div>
                    <div class="col-lg-4">
                        <h6><b>Status</b></h6>
                        <p><span class="badge badge-{{Helper::research_badge_status($research->status)}}">{{Helper::capitalize_text(str_replace('_', ' ', $research->status)) ?? 'N/A'}}</span></p>
                    </div>
                    <div class="col-lg-4">
                        <h6><b>Type</b></h6>
                        <p>{{$research->research_type->type ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <h6><b>Chapter</b></h6>
                        <p>{{$research->chapter ?? 'N/A'}}</p>
                    </div>
                    <div class="col-lg-4">
                        <h6><b>Version</b></h6>
                        <p>{{$research->version ?? 'N/A'}}</p>
                    </div>
                    <div class="col-lg-4">
                        <h6><b>Date Submitted</b></h6>
                        <p>{{$research->created_at->format("m/d/Y h:i A") ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <h6><b>Department</b></h6>
                        <p>{{$research->department->dept_name ?? 'N/A'}}</p>
                    </div>
                    <div class="col-lg-4">
                        <h6><b>Course</b></h6>
                        <p>{{$research->course->course_name ?? 'N/A'}}</p>
                    </div>
                    <div class="col-lg-4">
                        <h6><b>Yearlevel</b></h6>
                        <p>{{$research->yearlevel->yearlevel_name ?? 'N/A'}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Submission Overview</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <h6><b>Submitted By</b></h6>
                        <p>{{$research->submitted_by->name ?? 'N/A'}}</p>
                    </div>
                    <div class="col-lg-4">
                        <h6><b>Professor</b></h6>
                        <p>{{$research->submitted_to->name ?? 'N/A'}}</p>
                    </div>
                    <div class="col-lg-4">
                        <h6><b>Last Modified By</b></h6>
                        <p>{{$research->modified_by->name ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <h6><b>Processed By</b></h6>
                        <p>{{$research->processed_by->name ?? 'N/A'}}</p> 
                    </div>
                    <div class="col-lg-4">
                        <h6><b>Processed/Modified At</b></h6>
                        <p>{{$research->updated_at->format("m/d/Y h:i A") ?? 'N/A'}}</p>
                    </div>
                    <div class="col-lg-4">
                        <h6><b>Share File With</b></h6>
                        @forelse($research->shared as $author)
                        <span>{{$author->user->name}}</span><br>
                        @empty
                        <span>{{'N/A'}}</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div><hr>
        <div class="text-right">
            <a href="{{route('portal.student_research.index')}}" class="btn btn-sm btn-light">Close</a>
        </div>
    </div>
    <div class="col-sm-12 col-md-5 col-lg-4">
        <h2 class="section-title">Activities</h2>
        <div class="activities">
            @forelse($research->logs as $history)
            <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                    <i class="fas fa-arrows-alt"></i>
                </div>
                <div class="activity-detail">
                    <div class="mb-2">
                        <span class="text-job text-primary">{{$history->created_at->format("m/d/Y h:i A")}}</span>
                        <span class="bullet"></span>
                        <a class="text-job" href="#">Remark</a>
                    </div>
                    <p>{{$history->user->name}} - {{$history->remarks}}.</p>
                </div>
            </div>
            @empty
            <div class="activity">
                <div class="activity-icon bg-primary text-white shadow-primary">
                    <i class="fas fa-arrows-alt"></i>
                </div>
                <div class="activity-detail">
                    <p>No activities yet.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@stop

@section('page-scripts')
<script type="text/javascript">
    $(".status-approve").on('click', function(){
        var url = $(this).data('url');
        
        swal({
            title: "Are you sure you want to approve this research?",
            icon: "info",
            buttons: true,
            dangerMode: true,
        })
        .then((result) => {
            if(result){
                window.location.href = url;
            }
        });
    });

    $(".status-reject").on('click', function(){
        var url = $(this).data('url');
        
        swal({
            title: "Are you sure you want to reject this research?",
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