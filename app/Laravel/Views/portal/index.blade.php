@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Dashboard</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
@stop

@section('content')
@include('portal._components.notification')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon">
                <i class="fas fa-folder text-muted"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Research Documents</h4>
                </div>
                <div class="card-body">
                    {{$total_researches}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon">
                <i class="fas fa-copy text-muted"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Completed Research</h4>
                </div>
                <div class="card-body">
                    {{$total_completed_research}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon">
                <i class="fas fa-globe text-muted"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Posted Research</h4>
                </div>
                <div class="card-body">
                    {{$total_posted_research}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon">
                <i class="fas fa-users-cog text-muted"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Researchers</h4>
                </div>
                <div class="card-body">
                    {{$total_researchers}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon">
                <i class="fas fa-file text-muted"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Submitted Research</h4>
                </div>
                <div class="card-body">
                    {{$total_personal_research}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon">
                <i class="fas fa-file-alt text-muted"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Student Research</h4>
                </div>
                <div class="card-body">
                    {{$total_student_research}}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon">
                <i class="fas fa-archive text-muted"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Archives</h4>
                </div>
                <div class="card-body">
                    {{$total_archives}}
                </div>
            </div>
        </div>
    </div>   
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon">
                <i class="fas fa-users text-muted"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>User Application</h4>
                </div>
                <div class="card-body">
                    {{$total_applications}}
                </div>
            </div>
        </div>
    </div>                       
</div>
<div class="row">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Posted Research Statistics</h4>
            </div>
            <div class="card-body">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>All Research Status</h4>
            </div>
            <div class="card-body">
                <canvas id="myChart4"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Research Statistics</h4>
            </div>
            <div class="card-body">
                <canvas id="myChart2"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4>Completed Research</h4>
            </div>
            <div class="card-body">
                <canvas id="myChart3"></canvas>
            </div>
        </div>
    </div>   
</div>
@stop