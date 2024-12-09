@extends('portal._layouts.web')

@section('content')
@include('portal._components.notification')
<div class="section-header">
    <h1>Researches</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Researches</a></div>
        <div class="breadcrumb-item">Details</div>
    </div>
</div>
<div class="hero text-white hero-bg-image hero-bg-parallax mb-4" style="background-image: url('{{asset('assets/img/cc.jpg')}}');">
    <div class="hero-inner">
        <h2>Research Documents</h2>
        <p class="lead">Access a diverse collection of research documents.</p>
    </div>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Research Details</h4>
        </div>
        <div class="card-body">
            @if($research)
            {!!$research->abstract!!}                    
            @endif
        </div>
        <div class="card-footer text-right">
            <a href="{{route('portal.researches')}}" class="btn btn-sm btn-light">Go Back</a>
        </div>
    </div>
</div>
@stop