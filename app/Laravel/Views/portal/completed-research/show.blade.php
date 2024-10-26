@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Completed Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Completed Research</a></div>
        <div class="breadcrumb-item">Create</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-10 col-lg-8">
        @include('portal._components.notification')
        <div class="card">
            <div class="card-header">
                <h4>Details</h4>
                <div class="card-header-action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false" style="border-radius: 0.25rem !important;">Options</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 26px, 0px); top: 0px; left: 0px; will-change: transform;">
                            @if($completed_research->status == "re_submission")
                            <a class="dropdown-item" href="{{route('portal.completed_research.edit', [$completed_research->id])}}">Resubmit Research</a>
                            @endif
                            @if($completed_research->status == "for_posting")
                            <a class="dropdown-item" href="#">Post Research</a>
                            @endif
                            <a href="{{route('portal.completed_research.download', [$completed_research->id])}}" class="dropdown-item">Download File</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false" style="border-radius: 0.25rem !important;">Remarks</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 26px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a data-url="{{route('portal.completed_research.edit_status', ['id' => $completed_research->id, 'status' => "for_posting"])}}" class="dropdown-item status-for-posting" type="button" style="cursor: pointer;">For Posting</a>
                            <a data-url="{{route('portal.completed_research.edit_status', ['id' => $completed_research->id, 'status' => "re_submission"])}}" class="dropdown-item status-re-submission" type="button" style="cursor: pointer;">For Resubmission</a>
                            <a data-url="{{route('portal.completed_research.edit_status', ['id' => $completed_research->id, 'status' => "rejected"])}}" class="dropdown-item status-reject" type="button" style="cursor: pointer;">Reject Research</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Title</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$completed_research->title ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Department</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$completed_research->department->dept_name ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Course</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$completed_research->course->course_name ?? 'N/A'}} - {{$completed_research->yearlevel->yearlevel_name ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Status</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p><span class="badge badge-{{Helper::completed_badge_status($completed_research->status)}}">{{Helper::capitalize_text(str_replace('_', ' ', $completed_research->status)) ?? 'N/A'}}</span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Processor</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$completed_research->processor->name ?? 'Not Yet Processed'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Remarks</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$completed_research->remarks ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Authors</b></h6>
                    </div>
                    <div class="col-md-6">
                        @forelse($authors as $author)
                        <span>{{$author->name}}</span> 
                        @empty
                        <span>{{'N/A'}}</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Abstract</h4>
            </div>
            <div class="card-body">
                {!!$completed_research->abstract!!}
                <a href="{{route('portal.completed_research.index')}}" class="btn btn-sm btn-dark">Close Details</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('page-scripts')
<script type="text/javascript">
    $(".status-for-posting").on('click', function(){
        var url = $(this).data('url');
        
        swal({
            title: "Are you sure you want to post this research?",
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

    $(".status-re-submission").on('click', function(){
        var url = $(this).data('url');
        
        swal({
            title: "Are you sure you want this research to be re submit?",
            icon: "info",
            buttons: true,
            dangerMode: true,
            content: {
                element: "input",
                attributes: {
                    placeholder: "Enter Remarks*(required)",
                    type: "text",
                },
            },
        })
        .then((result) => {
            if(result){
                window.location.href = `${url}?remarks=${encodeURIComponent(result)}`;
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
            content: {
                element: "input",
                attributes: {
                    placeholder: "Enter Remarks*(required)",
                    type: "text",
                },
            },
        })
        .then((result) => {
            if(result){
                window.location.href = `${url}?remarks=${encodeURIComponent(result)}`;
            }
        });
    });
</script>
@stop