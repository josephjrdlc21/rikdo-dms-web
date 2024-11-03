@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Archives</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Archives</a></div>
        <div class="breadcrumb-item active"><a href="#">Completed</a></div>
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
            </div>
            <div class="card-footer text-right">
                <a href="{{route('portal.archives.completed')}}" class="btn btn-sm btn-light">Close</a>
            </div>
        </div>
    </div>
</div>
@stop