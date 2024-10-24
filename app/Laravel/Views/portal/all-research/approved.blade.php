@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>All Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">All Research</a></div>
        <div class="breadcrumb-item active"><a href="#">Approved</a></div>
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
                <a href="{{route('portal.all_research.approved')}}" class="btn btn-sm btn-secondary" style="border-radius: 0.25rem !important;">Reset Filter</a>
            </div>
        </div>
        <div class="card-body">
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
                        {!! html()->select('research_type', $research_types, value($selected_type), ['id' => "input_research_type"])->class('form-control selectric') !!}               
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_chapter">Chapter</label>
                        <input type="number" id="input_chapter" class="form-control" placeholder="0" name="chapter" value="{{$chapter}}" min="0">
                    </div>
                </div>
                <div class="col-sm-12 col-lg-4">
                    <div class="form-group">
                        <label for="input_version">Version</label>
                        <input type="number" id="input_version" class="form-control" placeholder="0.0" name="version" value="{{$version}}" step="0.1" min="0">
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
                        <th>No.</th>
                        <th class="text-center">Title</th>
                        <th>Type</th>
                        <th>Professor</th>
                        <th>Submitted By</th>
                        <th>Date Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>                         
                    @forelse($record as $index => $research)
                    <tr>
                        <td>{{$loop->index + $record->firstItem()}}</td>
                        <td class="text-center">
                            {{$research->title}}<br>
                            <small>ch {{$research->chapter}}, ver {{$research->version}}</small>
                        </td>
                        <td>{{$research->research_type->type}}</td>
                        <td>{{$research->submitted_to->name}}</td>
                        <td>{{$research->submitted_by->name}}</td>
                        <td>{{$research->created_at->format("m/d/Y h:i A")}}</td>
                        <td>
                            <div class="btn-group mb-2">
                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="{{route('portal.all_research.show', [$research->id])}}">View Details</a>
                                    <a class="dropdown-item delete-record" data-url="{{route('portal.all_research.delete', [$research->id])}}" type="button" style="cursor: pointer;">Delete Research</a>
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
            title: "Are you sure you want to delete this?",
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