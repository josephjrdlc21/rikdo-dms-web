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
                <div class="card-header-action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false" style="border-radius: 0.25rem !important;">Options</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 26px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a href="{{route('portal.posted_research.download', [$posted_research->id])}}" class="dropdown-item">Download File</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-center">{{$posted_research->title ?? 'N/A'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p>Dept: {{$posted_research->department->dept_name ?? 'N/A'}}<br>
                        Course: {{$posted_research->course->course_name ?? 'N/A'}}<br>
                        Year Level: {{$posted_research->yearlevel->yearlevel_name ?? 'N/A'}}<br>
                        Authors: 
                        @forelse($authors as $author)
                        <span>{{$author->name}}</span> 
                        @empty
                        <span>{{'N/A'}}</span>
                        @endforelse
                        <br>Posted by: {{$posted_research->processor->name ?? 'Not Yet Processed'}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        {!!$posted_research->abstract!!}                    
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p>Date: {{$posted_research->created_at->format("m/d/Y h:i A") ?? 'N/A'}}</p>
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