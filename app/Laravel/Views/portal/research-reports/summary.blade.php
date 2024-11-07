@extends('portal._layouts.main')

@section('breadcrumb')
<div class="section-header">
    <h1>Research Reports</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="#">Research Reports</a></div>
        <div class="breadcrumb-item active"><a href="#">Summary</a></div>
        <div class="breadcrumb-item">Data</div>
    </div>
</div>
@stop

@section('content')
@include('portal._components.notification')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-11 col-lg-10">
        <div class="card">
            <form method="GET" action="">
                <div class="card-header">
                    <h4>Filter Dates</h4>
                    <div class="card-header-action">
                        <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 0.25rem !important;">Filter</button>
                        <a href="{{route('portal.research_reports.summary')}}" class="btn btn-sm btn-secondary" style="border-radius: 0.25rem !important;">Reset</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_year">Years</label>
                                {!! html()->select('year', $years, $selected_year, ['id' => "input_year"])->class('form-control selectric') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input_month">Months</label>
                                {!! html()->select('month', $months, $selected_month, ['id' => "input_month"])->class('form-control selectric') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="invoice">
            <div class="invoice-print">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="invoice-title">
                            <h2>Report</h2>
                            <div class="invoice-number">
                                <img src="{{asset('assets/img/rikdo-logo.png')}}" alt="logo" width="80" class="img-fluid rounded-circle">
                            </div>
                        </div>
                    </div>
                </div><hr>
                <div class="row">
                    <div class="col-md-6">
                        <address>
                            <strong>Process By:</strong><br>
                            {{Helper::capitalize_text($auth->user_info->role)}}<br>
                            {{$auth->name}}
                        </address>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <address>
                            <strong>Print Date:</strong><br>
                            {{Carbon::now()->format('F j, Y')}}<br><br>
                        </address>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <div class="section-title">Researches Summary</div>
                        <p class="section-lead">All items here cannot be deleted.</p>
                    </div>
                    <div class="col-lg-4 text-right">
                        <div class="invoice-detail-item">
                        <div class="invoice-detail-name">Researches Total</div>
                        <div class="invoice-detail-value">{{$research_total}}</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-md">
                                <tr>
                                    <th>No.</th>
                                    <th>Statuses</th>
                                    <th class="text-center">Quantity</th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Pending</td>
                                    <td class="text-center">{{$research_total_pending}}</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>For Revision</td>
                                    <td class="text-center">{{$research_total_for_revision}}</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Approved</td>
                                    <td class="text-center">{{$research_total_approved}}</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Rejected</td>
                                    <td class="text-center">{{$research_total_rejected}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <div class="section-title">Complete Research Summary</div>
                        <p class="section-lead">All items here cannot be deleted.</p>
                    </div>
                    <div class="col-lg-4 text-right">
                        <div class="invoice-detail-item">
                        <div class="invoice-detail-name">Complete Research Total</div>
                        <div class="invoice-detail-value">{{$completed_total}}</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-md">
                                <tr>
                                    <th>No.</th>
                                    <th>Statuses</th>
                                    <th class="text-center">Quantity</th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Pending</td>
                                    <td class="text-center">{{$completed_total_pending}}</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Re Submission</td>
                                    <td class="text-center">{{$completed_total_re_submission}}</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>For Posting</td>
                                    <td class="text-center">{{$completed_total_for_posting}}</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Rejected</td>
                                    <td class="text-center">{{$completed_total_rejected}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <div class="section-title">Posted Research Summary</div>
                        <p class="section-lead">All items here cannot be deleted.</p>
                    </div>
                    <div class="col-lg-4 text-right">
                        <div class="invoice-detail-item">
                        <div class="invoice-detail-name">Posted Research Total</div>
                        <div class="invoice-detail-value">{{$total_posted}}</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-md">
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-right">Date</th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Posted</td>
                                    <td class="text-center">{{$total_posted}}</td>
                                    <td class="text-right">10/09/2024</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div><hr>
            <div class="text-md-right">
                <a href="{{route('portal.index')}}" class="btn btn-danger btn-icon icon-left"><i class="fas fa-times"></i> Cancel</a>
                <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
            </div>
        </div>
    </div>
</div>
@stop