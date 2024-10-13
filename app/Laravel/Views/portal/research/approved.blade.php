@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>My Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">My Research</a></div>
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
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_keyword">Keyword</label>
                        <input type="text" id="input_keyword" class="form-control" placeholder="eg. Title, Prof, Researcher" name="keyword" value="{{$keyword}}">
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_from">From</label>
                        <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" name="start_date" value="{{$start_date}}">                            
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_to">To</label>
                        <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" name="end_date" value="{{$end_date}}">                            
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_research_type">Research Type</label>
                        <input type="text" id="" class="form-control" placeholder="All" name="" value="">
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_chapter">Chapter</label>
                        <input type="text" id="" class="form-control" placeholder="0" name="" value="">
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_version">Version</label>
                        <input type="text" id="" class="form-control" placeholder="0.0" name="" value="">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Apply Filter</button>
            <a href="{{route('portal.research.approved')}}" class="btn btn-sm btn-secondary">Reset Filter</a>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h4>Record Data</h4>
        <div class="card-header-action">
            <a href="#" class="btn btn-sm btn-primary" style="border-radius: 0.25rem !important;">Create Research</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th class="text-center">Chapter</th>
                        <th class="text-center">Version</th>
                        <th>Submitted By</th>
                        <th>Submitted To</th>
                        <th>Date Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>                         
                    @forelse($record as $index => $research)
                    <tr>
                        <td>{{$loop->index + $record->firstItem()}}</td>
                        <td>{{$research->title}}</td>
                        <td><span class="badge badge-{{Helper::research_badge_status($research->status)}}">{{Helper::capitalize_text($research->status)}}</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{$research->created_at->format("m/d/Y")}}<br><small>{{$research->created_at->format("h:i A")}}</small></td>
                        <td>
                            <div class="btn-group mb-2">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="#">View Details</a>
                                </div>
                            </div> 
                        </td>
                    </tr>
                    @empty
                    <td colspan="9">
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