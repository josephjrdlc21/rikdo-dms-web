@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Audit Trail</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Audit Trail</a></div>
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
                <a href="{{route('portal.audit_trail.index')}}" class="btn btn-sm btn-secondary" style="border-radius: 0.25rem !important;">Reset Filter</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 col-lg-5">
                    <div class="form-group">
                        <label for="input_keyword">Keyword</label>
                        <input type="text" id="input_keyword" class="form-control" placeholder="eg. Username, Remarks" name="keyword" value="{{$keyword}}">
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
            <a href="{{route('portal.audit_trail.export')}}?file_type=excel&keyword={{$keyword}}&start_date={{$start_date}}
            &end_date={{$end_date}}" class="btn btn-sm btn-success" style="border-radius: 0.25rem !important;">Export Excel</a>
            <a href="{{route('portal.audit_trail.export')}}?file_type=pdf&keyword={{$keyword}}&start_date={{$start_date}}
            &end_date={{$end_date}}" class="btn btn-sm btn-danger" style="border-radius: 0.25rem !important;">Export PDF</a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>IP Address</th>
                        <th>Action</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>                         
                    @forelse($record as $index => $audit_trail)
                    <tr>
                        <td>{{$loop->index + $record->firstItem()}}</td>
                        <td>{{$audit_trail->user->name}}<br><small>{{Helper::capitalize_text($audit_trail->user->user_info->role) ?? ''}}</small></td>
                        <td>{{$audit_trail->ip}}</td>
                        <td>{{$audit_trail->remarks}}</td>
                        <td>{{$audit_trail->created_at->format("m/d/Y h:i A")}}</td>
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
        <div class="m-4">
            <div class="mb-2">Showing <strong>{{$record->firstItem()}}</strong> to <strong>{{$record->lastItem()}}</strong> of <strong>{{$record->total()}}</strong> entries</div>
            <div>{!!$record->appends(request()->query())->render()!!}</div>
        </div>
        @endif
    </div>
</div>
@stop