@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>CMS - Roles</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">CMS</a></div>
        <div class="breadcrumb-item"><a href="#">Permissions</a></div>
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
                <div class="col-sm-12 col-lg-5">
                    <div class="form-group">
                        <label for="input_keyword">Keyword</label>
                        <input type="text" id="input_keyword" class="form-control" placeholder="eg. Module, Permission" name="keyword"  value="{{$keyword}}">
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
            <button type="submit" class="btn btn-sm btn-primary">Apply Filter</button>
            <a href="{{route('portal.cms.permissions.index')}}" class="btn btn-sm btn-secondary">Reset Filter</a>
        </form>
    </div>
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
                        <th>Module</th>
                        <th>Permission</th>
                        <th>Date Added</th>
                        <th>Last Modified</th>
                    </tr>
                </thead>
                <tbody>                         
                    @forelse($record as $index => $permission)
                    <tr>
                        <td>{{$loop->index + $record->firstItem()}}</td>
                        <td>{{$permission->module_name}}</td>
                        <td>
                            <div>{{$permission->description}}</div>
                            <div class="text-primary">{{$permission->name}}</div>
                        </td>
                        <td>{{$permission->created_at->format("m/d/Y h:i A")}}</td>
                        <td>{{$permission->updated_at->format("m/d/Y h:i A")}}</td>
                    </tr>
                    @empty
                    <td colspan="5">
                        <p class="text-center">No record found yet.</p>
                    </td>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($record->total() > 0)
        <div class="d-sm-flex align-items-center justify-content-between px-4 mt-2 mb-2">
            <div>Showing <strong>{{$record->firstItem()}}</strong> to <strong>{{$record->lastItem()}}</strong> of <strong>{{$record->total()}}</strong> entries</div>
            <div>{!!$record->appends(request()->query())->render()!!}</div>
        </div>
        @endif
    </div>
</div>
@stop