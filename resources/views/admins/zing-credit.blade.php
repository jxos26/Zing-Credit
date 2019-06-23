@extends('layouts.base')

@section('pageTitle', 'ZING CREDIT')

@section('pagespecificstylesheet')
<!-- Page plugins -->
<link rel="stylesheet" href="../assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="../assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="../assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">

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
            <div class="col-2">
                <form _lpchecked="1" class="">
                    <div class="form-group">
                        <select name=""  class="form-control" id="">
                                <option value=""> Select Account</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-3 offset-9">
            <form _lpchecked="1" class="">
            <div class="form-group">
                        <input class="form-control datepicker" placeholder="Select date" type="text" >
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <div class="card shadow">                    
                    <div class="card-body">
                        <div class="row row-1">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 text-center">
                                <img src="/images/arrow-up.png" alt="">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <h5>Total Lead Count</h5>
                                <h4>5,000</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="row row-2">
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12  text-center">
                                <img src="/images/dollar-sign.png" alt="">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
                                <h5>Total Pre-qualification Amount</h5>
                                <h4>$5,000</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 mb-5 mb-xl-0">
                <div class="card  shadow">
                    <div class="card-header bg-transparent">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="text-dark mb-0">Total Leads By Months</h2>
                            </div>
                            <div class="col">
                                <ul class="nav nav-pills justify-content-end">
                                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales"
                                        data-update='{"data":{"datasets":[{"data":[0, 15, 20, 10, 25, 30, 15, 35, 40, 20, 60, 60]}]}}'
                                        data-prefix="$" data-suffix="k">
                                        Last 12 Months
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart">
                            <!-- Chart wrapper -->
                            <canvas id="chart-sales" class="chart-canvas"></canvas>
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
                                    <th>Customer First Name</th>
                                    <th>Customer Last Name</th>
                                    <th>Customer Phone</th>
                                    <th>Customer Email</th>
                                    <th>Customer Street</th>
                                    <th>Customer City</th>
                                    <th>Customer Zip</th>
                                    <th>Pre-equal Amount</th>
                                    <th>Client Name</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Customer First Name</th>
                                    <th>Customer Last Name</th>
                                    <th>Customer Phone</th>
                                    <th>Customer Email</th>
                                    <th>Customer Street</th>
                                    <th>Customer City</th>
                                    <th>Customer Zip</th>
                                    <th>Pre-equal Amount</th>
                                    <th>Client Name</th>
                                    <th>Date</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011/04/25</td>
                                    <td>$320,800</td>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>2011/04/25</td>
                                </tr>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011/04/25</td>
                                    <td>$320,800</td>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>2011/04/25</td>
                                </tr>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011/04/25</td>
                                    <td>$320,800</td>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>2011/04/25</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




        @endsection

        @section('pagespecificscripts')

        <script src="../assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <!-- Optional JS -->
        <script src="../assets/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="../assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="../assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="../assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="../assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="../assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="../assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="../assets/vendor/datatables.net-select/js/dataTables.select.min.js"></script>



        <!-- Optional JS -->
        <script src="../assets/vendor/chart.js/dist/Chart.min.js"></script>
        <script src="../assets/vendor/chart.js/dist/Chart.extension.js"></script>


        @stop