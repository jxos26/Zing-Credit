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
    <div class="container-fluid mt--7">

        <div class="row">
            <div class="col-1 offset-10">
                <button class="btn btn-icon btn-info" type="button" data-toggle="modal" data-target="#addUserModal">
                    <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                    <span class="btn-inner--text">ADD USER</span>
                </button>
            </div>
        </div>
        <br />
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header">
                        <h3 class="mb-0">User List</h3>
                        <br />
                        @if (session('status'))
                        <div id="alert-action" class="alert alert-success" role="alert">
                            <strong>Success!</strong> {{ session('status') }}
                        </div>
                        @elseif (session('error'))
                        <div id="alert-action" class="alert alert-danger" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                        @endif
                    </div>
                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-buttons">
                            <thead class="thead-light">
                                <tr>
                                    <th>Clients</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Clients</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @if($users)
                                @foreach($users as $l)
                                <tr>
                                    <td>{{$l->client}}</td>
                                    <td>
                                        <img src="/{{$l->img}}" alt="{{$l->firstname .' '. $l->lastname}}"
                                            class="rounded-circle" width="40" height="40">
                                        {{$l->firstname.' '. $l->lastname}}
                                    </td>
                                    <td>{{$l->email}}</td>
                                    <td>{{$l->status}}</td>
                                    <td>
                                        <button class="btn btn-icon btn-warning" id="settings" data-id="{{$l->id}}"
                                            data-firstname="{{$l->firstname}}" data-middlename="{{$l->middlename}}"
                                            data-lastname="{{$l->lastname}}" data-email="{{$l->email}}"
                                            data-status="{{$l->status}}" data-client="{{$l->client}}"
                                            data-toggle="modal" data-target="#updateUserModal">
                                            <span class="btn-inner--icon"><i class="ni ni-settings-gear-65"></i></span>
                                            <span class="btn-inner--text">Settings</span>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Registraion Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="modal-notification"
            aria-modal="true" style="padding-right: 17px;">
            <div class="modal-dialog modal-info modal-dialog-centered modal-" role="document">
                <div class="modal-content bg-gradient-info">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modal-title-notification">Register New User</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="py-3">
                            <form role="form" method="POST" action="{{ route('user.register') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="First Name..." name="firstname"
                                            type="text" required>
                                        @error('firstname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Middle Name..." name="middlename"
                                            type="text">
                                        @error('middlename')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Last Name..." name="lastname"
                                            type="text" required>
                                        @error('lastname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Email..." name="email" type="email"
                                            required>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-radio mb-3">
                                        <input name="option" class="custom-control-input" id="customRadio5" type="radio"
                                            value="send">
                                        <label class="custom-control-label" for="customRadio5">Send Password Through
                                            Email</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-3">
                                        <input name="option" class="custom-control-input" id="customRadio6" type="radio"
                                            value="create">
                                        <label class="custom-control-label" for="customRadio6">Create Password</label>
                                    </div>
                                    <div class="input-group input-group-alternative" id="password"
                                        style="display:none;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Password..." name="password"
                                            type="password">

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary"> <i class="ni ni-send"></i>
                                        Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Update Modal -->
        <div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="modal-notification"
            aria-modal="true" style="padding-right: 17px;">
            <div class="modal-dialog  modal-info modal-dialog-centered modal-" role="document">
                <div class="modal-content bg-gradient-info">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modal-title-notification">Update Settings</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="py-3">


                            <form id="update-settings" role="form" method="POST"
                                action="{{ route('update.user.settings') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="First Name..." name="firstname"
                                            id="firstname" type="text" required>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Middle Name..." name="middlename"
                                            id="middlename" type="text">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Last Name..." name="lastname"
                                            id="lastname" type="text" required>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Email..." name="email" type="email"
                                            id="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="Password..." name="password"
                                            type="password">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-ui-04"></i></span>
                                        </div>
                                        <select name="status" id="status" class="form-control">

                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-bullet-list-67"></i></span>
                                        </div>
                                        <select name="client" id="client" class="form-control">
                                        </select>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <input class="form-control" name="user_id" id="user_id" type="hidden">
                                    <button type="submit" class="btn btn-primary"> <i class="ni ni-send"></i>
                                        Update Settings</button>
                                </div>
                            </form>
                        </div>
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


        <script>
        $(document).ready(function() {
            $("#customRadio6").click(function() {
                $('#password').show();
            });
            $("#customRadio5").click(function() {
                $('#password').hide();
            });
        });


        $(document).on("click", "#settings", function() {




            $('#update-settings #firstname').val($(this).data('firstname'));
            $('#update-settings #middlename').val($(this).data('middlename'));
            $('#update-settings #lastname').val($(this).data('lastname'));
            $('#update-settings #email').val($(this).data('email'));
            $('#update-settings #user_id').val($(this).data('id'));

            $('#update-settings #status')
                .find('option')
                .remove()
                .end();

            $("#update-settings #status").append(new Option("ACTIVE", "ACTIVE"));
            $("#update-settings #status").append(new Option("INACTIVE", "INACTIVE"));

            if($(this).data('status') === "ACTIVE"){
                $("#update-settings #status").val("ACTIVE").prop('selected', true);
            }else{
                $("#update-settings #status").val("INACTIVE").prop('selected', true);
            }

            
            $('#update-settings #client')
                .find('option')
                .remove()
                .end();
            if ($(this).data('client') != '') {
                $("#update-settings #client").val($(this).data('client')).prop('selected', true);
            } else {
                $("#update-settings #client").append(new Option("Select Client", "Select Client"));
                $("#update-settings #client").val($(this).data('Select Client')).prop('selected', true);
            }

            $.ajax({
                url: '/admin/get-clients',
                type: 'get',
                data: {},
                dataType: 'json',
                success: function(response) {
                    if (response.success === true) {

                        $.each(response.clients, function(index, value) {
                            console.log(value.client);
                            $("#update-settings #client").append(new Option(value.client,
                                value.client));
                        });
                    }
                }

            });

            



        });
        </script>



        @stop