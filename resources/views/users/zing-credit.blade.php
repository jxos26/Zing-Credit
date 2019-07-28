@extends('layouts.base')

@section('pageTitle', 'ZING CREDIT')

@section('pagespecificstylesheet')
<!-- Page plugins -->
<link rel="stylesheet" href="/assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="/assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">

@stop

@section('content')
<!-- Main content -->
<div class="main-content">

    @include('layouts.top-nav')

    <!-- Header -->
    <div class="header bg-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">

            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--9">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if(Auth::user()->type == "ADMIN")
                <div class="dropdown">
                    <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"
                        id="btnCompanyRecord" data-selected="{{$company_title}}">Select Company
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu" id="selectedCompanyRecord">
                        @if($company)
                        @foreach($company as $l)
                        <li><a href="/zing-credit/company/{{$l->company}}">{{$l->company}}</a></li>
                        @endforeach
                        @endif
                    </ul>
                </div>
                @endif

                <div class="dropdown">
                    <button class="btn btn-info dropdown-toggle" type="button" id="btnCustomRecord"
                        data-selected="{{$leads_filter}}" data-toggle="dropdown">Select Custom
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu" id="selectedCustomRecord">
                        <li><a data-toggle="modal" data-target="#selectCustom" data-selected="Date Range">Date
                                Range</a></li>
                        <li><a href="{{ route('zing.credit.today') }}" data-selected="Today">Today</a></li>
                        <li><a href="{{ route('zing.credit.yesterday') }}" data-selected="Yesterday">Yesterday</a></li>
                        <li><a href="{{ route('zing.credit.Last7Days') }}" data-selected="Last 7 Days">Last 7 Days</a>
                        </li>
                        <li><a href="{{ route('zing.credit.Last30Days') }}" data-selected="Last 30 Days">Last 30
                                Days</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <!-- <div class="col-xl-4">
                <div class="card shadow">                    
                <div class="card-body">
                        <div class="row row-1">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 text-center">
                                <img src="/images/arrow-up.png" class="img-fluid" alt="">
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <h5>Total Lead Count</h5>
                                <h4>5,000</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row row-2">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12  text-center">
                                <img src="/images/dollar-sign.png"  class="img-fluid" alt="">
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                <h5>Total Pre-qualification Amount</h5>
                                <h4>$5,000</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card  shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="text-dark mb-0">Total Leads By {{date('Y')}}</h2>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales"
                                        data-update='' data-prefix="$" data-suffix="k" id="title_filter">

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="chart-sales" data-labels="{{$labels}}" data-datas="{{$datas}}"
                                data-leadsFilter="{{$leads_filter}}" class="chart-canvas"></canvas>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <br /><br />

        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">All Leads Details</h3>
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th>Company</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Apartment</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Vehicle Category</th>
                                    <th>Vehicle Make</th>
                                    <th>Date</th>
                                    <th>Score</th>
                                    <th>Pre-equal Amount</th>
                                    <th>Leads Date</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if($leads)
                                @foreach($leads as $l)
                                <tr>
                                    <td>{{$l->company}}</td>
                                    <td>{{$l->name}}</td>
                                    <td>{{$l->email}}</td>
                                    <td>{{$l->mphone}}</td>
                                    <td>{{$l->address }}</td>
                                    <td>{{$l->apartment }}</td>
                                    <td>{{$l->city}}</td>
                                    <td>{{$l->state}}</td>
                                    <td>{{$l->category}}</td>
                                    <td>{{$l->make}}</td>
                                    <td>{{date('F d, Y', strtotime($l->dob))}}</td>
                                    <td>{{$l->score}}</td>
                                    @if($l->amount == "No Match")
                                    <td>$l->amount</td>
                                    @else
                                    <td>${{number_format($l->amount,2)}}</td>
                                    @endif
                                    <td>{{date('F d, Y h:i a', strtotime($l->created_at))}}</td>

                                </tr>
                                @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="selectCustom" tabindex="-1" role="dialog" aria-labelledby="modal-notification"
            aria-modal="true" style="padding-right: 17px;">
            <div class="modal-dialog modal-info modal-dialog-centered modal-" role="document">
                <div class="modal-content bg-gradient-info">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modal-title-notification">Select Records By Date Range </h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="py-3">

                            <form method="POST" action="{{ route('zing.credit.DateRange') }}" id="date_range"
                                _lpchecked="1" class="">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control datepicker" name="start_date" placeholder="Start Date"
                                        type="text" required>
                                    <input class="form-control datepicker" name="end_date" placeholder="End Date"
                                        type="text" required>
                                    <input type="submit" class="btn btn-success" name="go" value="Search">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        @endsection

        @section('pagespecificscripts')

        <script src="/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <!-- Optional JS -->
        <script src="/assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="/assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="/assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="/assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="/assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="/assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>



        <!-- Optional JS -->
        <script src="/assets/vendor/chart.js/dist/Chart.min.js"></script>
        <script src="/assets/vendor/chart.js/dist/Chart.extension.js"></script>



        <script>
        $(document).ready(function() {
            var v = $("#btnCustomRecord").data('selected');
            $("#title_filter").text(v.toUpperCase());
            if (v !== '') {
                if (window.location.href.indexOf("last-30-days") > -1 || window.location.href.indexOf(
                        "last-7-days") > -1 || window.location.href.indexOf("today") > -1 || window.location
                    .href.indexOf("yesterday") > -1) {
                    $("#btnCustomRecord").text(v);
                } else {
                    $("#btnCustomRecord").text("Select Custom");
                }
            }

            var c = $("#btnCompanyRecord").data('selected');

            if (c !== ''){
                $("#btnCompanyRecord").text(c);
            }


        });
        </script>

        @stop