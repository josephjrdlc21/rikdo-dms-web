@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>CMS - Pages</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">CMS</a></div>
        <div class="breadcrumb-item"><a href="#">Pages</a></div>
        <div class="breadcrumb-item">Create</div>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>{{$page->title ?? "N/A"}}</h4>
            </div>
            <div class="card-body">
                {!!$page->content!!}
            </div>
            <div class="card-footer text-right">
                <a href="{{route('portal.cms.pages.edit', [$page->id])}}" class="btn btn-sm btn-warning">Edit</a>
                <a href="{{route('portal.cms.pages.index')}}" class="btn btn-sm btn-light">Close</a>
            </div>
        </div>
    </div>
</div>
@stop