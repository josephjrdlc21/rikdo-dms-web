@extends('portal._layouts.web')

@section('content')
<div class="section-header">
    <h1>Statistics</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Statistics</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
<div class="hero text-white hero-bg-image hero-bg-parallax mb-4" style="background-image: url('{{asset('assets/img/cc.jpg')}}');">
    <div class="hero-inner">
        <h2>Research Statistics</h2>
        <p class="lead">Explore comprehensive research statistics that provide insights into trends, and impacts.</p>
    </div>
</div>
<div class="section-body">
    <div class="row">
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Research Past 5 Years</h4>
                </div>
                <div class="card-body">
                    <canvas id="myChart5"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Researchers Past 5 Years</h4>
                </div>
                <div class="card-body">
                    <canvas id="myChart8"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Research Insights</h4>
                </div>
                <div class="card-body">
                    <canvas id="myChart6"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>Total Research</h4>
                </div>
                <div class="card-body">
                    <canvas id="myChart7"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('chart-scripts')
<script type="text/javascript">
    var postedStatisticsData = {!! json_encode($posted_statistics_data) !!};
    var researchersStatisticsData = {!! json_encode($researchers_statistics_data) !!};
    var researchStatusData = {!! json_encode($research_status_data) !!};
    var totalResearch = {!! json_encode($total_posted_research) !!};
</script>
@stop

@section('page-scripts')
<script src="{{asset('assets/js/page/custom-chart.js')}}"></script>
@stop