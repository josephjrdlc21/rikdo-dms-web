@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Completed Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Completed Research</a></div>
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
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h4>Record Data</h4>
    </div>
    <div class="card-body">
    </div>
</div>
@stop