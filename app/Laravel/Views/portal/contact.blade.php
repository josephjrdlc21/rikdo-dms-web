@extends('portal._layouts.web')

@section('content')
<div class="section-header">
    <h1>Contact</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Contact</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
<div class="hero text-white hero-bg-image hero-bg-parallax mb-4" style="background-image: url('{{asset('assets/img/rikdo.png')}}');">
    <div class="hero-inner">
        <h2>Contact Us</h2>
        <p class="lead">Get in touch with us for inquiries, support, or collaboration opportunities.</p>
    </div>
</div>
<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>{{$contact->title ?? "N/A"}}</h4>
        </div>
        <div class="card-body">
            {!!$contact->content ?? "N/A"!!}
        </div>
    </div>
</div>
@stop