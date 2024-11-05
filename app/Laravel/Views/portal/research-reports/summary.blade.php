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
                            Research Director<br>
                            John Dish Doe
                        </address>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <address>
                            <strong>Print Date:</strong><br>
                            September 19, 2018<br><br>
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
                        <div class="invoice-detail-value">100</div>
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
                                    <th class="text-right">Date</th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Pending</td>
                                    <td class="text-center">100</td>
                                    <td class="text-right">10/09/2024</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>For Revision</td>
                                    <td class="text-center">100</td>
                                    <td class="text-right">10/09/2024</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Approved</td>
                                    <td class="text-center">100</td>
                                    <td class="text-right">10/09/2024</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Rejected</td>
                                    <td class="text-center">100</td>
                                    <td class="text-right">10/09/2024</td>
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
                        <div class="invoice-detail-value">100</div>
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
                                    <th class="text-right">Date</th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Pending</td>
                                    <td class="text-center">100</td>
                                    <td class="text-right">10/09/2024</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Re Submission</td>
                                    <td class="text-center">100</td>
                                    <td class="text-right">10/09/2024</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>For Posting</td>
                                    <td class="text-center">100</td>
                                    <td class="text-right">10/09/2024</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Rejected</td>
                                    <td class="text-center">100</td>
                                    <td class="text-right">10/09/2024</td>
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
                        <div class="invoice-detail-value">100</div>
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
                                    <td class="text-center">100</td>
                                    <td class="text-right">10/09/2024</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div><hr>
            <div class="text-md-right">
                <button class="btn btn-danger btn-icon icon-left"><i class="fas fa-times"></i> Cancel</button>
                <button class="btn btn-warning btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
            </div>
        </div>
    </div>
</div>
@stop