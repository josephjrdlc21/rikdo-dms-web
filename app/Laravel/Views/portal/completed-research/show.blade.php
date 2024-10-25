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
@include('portal._components.notification')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-10 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Completed Research Details</h4>
                <div class="card-header-action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false" style="border-radius: 0.25rem !important;">Options</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 26px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a href="{{route('portal.completed_research.download', [$completed_research->id])}}" class="dropdown-item">Download File</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false" style="border-radius: 0.25rem !important;">Remarks</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 26px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a href="#" class="dropdown-item">For Posting</a>
                            <a href="#" class="dropdown-item">For Resubmission</a>
                            <a href="#" class="dropdown-item">Reject Research</a>
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
                        <p><span class="badge badge-{{Helper::completed_badge_status($completed_research->status)}}">{{Helper::capitalize_text($completed_research->status) ?? 'N/A'}}</span></p>
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