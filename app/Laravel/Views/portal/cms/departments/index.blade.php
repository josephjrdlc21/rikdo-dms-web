@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>CMS - Departments</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">CMS</a></div>
        <div class="breadcrumb-item"><a href="#">Departments</a></div>
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
                <a href="{{route('portal.cms.departments.index')}}" class="btn btn-sm btn-secondary" style="border-radius: 0.25rem !important;">Reset Filter</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-lg-5">
                    <div class="form-group">
                        <label for="input_keyword">Keyword</label>
                        <input type="text" id="input_keyword" class="form-control" placeholder="eg. Code, Name" name="keyword" value="{{$keyword}}">
                    </div>
                </div>
                <div class="col-sm-12 col-lg-7">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_from">From</label>
                                <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" name="start_date" value="{{$start_date}}">                            
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_to">To</label>
                                <input type="text" class="form-control datepicker" placeholder="YYYY-MM-DD" name="end_date" value="{{$end_date}}">                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="card">
    <div class="card-header">
        <h4>Record Data</h4>
        <div class="card-header-action">
            <a href="{{route('portal.cms.departments.create')}}" class="btn btn-sm btn-primary" style="border-radius: 0.25rem !important;">Create Department</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Date Added</th>
                        <th>Last Modified</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>                         
                    @forelse($record as $index => $department)
                    <tr>
                        <td>{{$loop->index + $record->firstItem()}}</td>
                        <td>{{strtoupper($department->dept_code)}}</td>
                        <td>{{$department->dept_name}}</td>
                        <td>{{$department->created_at->format("m/d/Y h:i A")}}</td>
                        <td>{{$department->updated_at->format("m/d/Y h:i A")}}</td>
                        <td>
                            <div class="btn-group mb-2">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 29px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="{{route('portal.cms.departments.edit', [$department->id])}}">Edit Details</a>
                                    <a class="dropdown-item delete-record" data-url="{{route('portal.cms.departments.delete', [$department->id])}}" type="button" style="cursor: pointer;">Delete Department</a>
                                </div>
                            </div> 
                        </td>
                    </tr>
                    @empty
                    <td colspan="6">
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