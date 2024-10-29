@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>My Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">All Research</a></div>
        <div class="breadcrumb-item active"><a href="#">Show</a></div>
        <div class="breadcrumb-item">Details</div>
    </div>
</div>
@stop

@section('content')
@include('portal._components.notification')
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h4>Research Details</h4>
                <div class="card-header-action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" class="btn btn-light dropdown-toggle" aria-expanded="false" style="border-radius: 0.25rem !important;">Options</a>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 26px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a href="{{route('portal.all_research.download', [$research->id])}}" class="dropdown-item">Download File</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <h6><b>Title</b></h6>
                            <p>{{$research->title ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Chapter</b></h6>
                            <p>{{$research->research_type->type ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Chapter</b></h6>
                            <p>{{$research->chapter ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Version</b></h6>
                            <p>{{$research->version ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Department</b></h6>
                            <p>{{$research->department->dept_name ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Date Submitted</b></h6>
                            <p>{{$research->created_at->format("m/d/Y h:i A") ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Last Modified By</b></h6>
                            <p>{{$research->modified_by->name ?? 'N/A'}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <h6><b>Status</b></h6>
                            <p><span class="badge badge-{{Helper::research_badge_status($research->status)}}">{{Helper::capitalize_text(str_replace('_', ' ', $research->status)) ?? 'N/A'}}</span></p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Submitted By</b></h6>
                            <p>{{$research->submitted_by->name ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Professor</b></h6>
                            <p>{{$research->submitted_to->name ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Course</b></h6>
                            <p>{{$research->course->course_name ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Department</b></h6>
                            <p>{{$research->yearlevel->yearlevel_name ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Processed/Modified At</b></h6>
                            <p>{{$research->updated_at->format("m/d/Y h:i A") ?? 'N/A'}}</p>
                        </div>
                        <div class="mb-2">
                            <h6><b>Processed By</b></h6>
                            <p>{{$research->processed_by->name ?? 'N/A'}}</p>
                        </div>
                    </div><hr>
                    <div class="col-sm-12">
                        <div class="mb-2">
                            <h6><b>Share File With</b></h6>
                            @forelse($research->shared as $author)
                            <span>{{$author->user->name}}</span><br>
                            @empty
                            <span>{{'N/A'}}</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="{{route('portal.all_research.index')}}" class="btn btn-sm btn-dark">Close Details</a>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h4>History</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Remarks</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($research->logs as $history)
                            <tr>
                                <td>{{$history->user->name}}</td>
                                <td>{{$history->remarks}}</td>
                                <td>{{$history->created_at->format("m/d/Y")}}<br><small>{{$history->created_at->format("h:i A")}}</small></td>
                            </tr>
                            @empty
                            <td colspan="3">
                                <p class="text-center">No history yet.</p>
                            </td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop