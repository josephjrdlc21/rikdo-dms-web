@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Posted Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Posted Research</a></div>
        <div class="breadcrumb-item">Details</div>
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
                @if($auth->canAny(['portal.posted_research.download'], 'web'))         
                <div class="card-header-action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false" style="border-radius: 0.25rem !important;">Options</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 26px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a href="{{route('portal.posted_research.download', [$posted_research->id])}}" class="dropdown-item">Download File</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Title</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$posted_research->title ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Department</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$posted_research->department->dept_name ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Course</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$posted_research->course->course_name ?? 'N/A'}} - {{$completed_research->yearlevel->yearlevel_name ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Posted By</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$posted_research->processor->name ?? 'Not Yet Processed'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6><b>Date Posted</b></h6>
                    </div>
                    <div class="col-md-6">
                        <p>{{$posted_research->created_at->format("m/d/Y h:i A") ?? 'N/A'}}</p>
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
                <div class="row">
                    <div class="col-12">
                        {!!$posted_research->abstract!!}                    
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="{{route('portal.posted_research.index')}}" class="btn btn-sm btn-light">Close</a>
            </div>
        </div>
    </div>
</div>
@stop