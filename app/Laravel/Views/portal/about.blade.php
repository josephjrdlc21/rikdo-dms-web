@extends('portal._layouts.web')

@section('content')
<div class="section-header">
    <h1>About</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">About</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
<div class="hero text-white hero-bg-image hero-bg-parallax mb-4" style="background-image: url('{{asset('assets/img/rikdo.png')}}');">
    <div class="hero-inner">
        <h2>About Us</h2>
        <p class="lead">Dedicated to knowledge-sharing by providing a collaborative platform for researchers.</p>
    </div>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>{{$about->title ?? "N/A"}}</h4>
        </div>
        <div class="card-body">
            {!!$about->content ?? "N/A"!!}
        </div>
    </div>
</div>
@stop