@extends('portal._layouts.web')

@section('content')
<div class="section-header">
    <h1>Posted Research</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Posted Research</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
<div class="hero text-white hero-bg-image hero-bg-parallax mb-4" style="background-image: url('{{asset('assets/img/rikdo.png')}}');">
    <div class="hero-inner">
        <h2>Research Hub</h2>
        <p class="lead">A centralized platform designed to facilitate collaboration, access, and dissemination of academic and professional research.</p>
    </div>
</div>
<div class="section-body">
    <div class="card">
        <form method="GET" action="">
            <div class="card-header">
                <h4>List of Research</h4>
                <div class="card-header-action">
                    <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 0.25rem !important;">Apply Filter</button>
                    <a href="{{route('portal.home')}}" class="btn btn-sm btn-secondary" style="border-radius: 0.25rem !important;">Reset Filter</a>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    {!! html()->select('posted_research', $posted_research, $selected_posted_research, ['id' => "input_posted_research"])->class('form-control select2') !!}
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>Abstract</h4>
        </div>
        <div class="card-body">
            @if($record)
            {!!$record->abstract!!}                    
            @endif
        </div>
    </div>
</div>
@stop