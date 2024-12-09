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
            <h4>Contact Information</h4>
        </div>
        <div class="card-body">
            <p class="text-center">
                Location: 1 1st Street New Asinan , Olongapo, Philippines<br>
                Contact: 0945 468 5377<br>
                Email: rikdo.cci@gmail.com
            </p>
        </div>
    </div>
</div>
@stop