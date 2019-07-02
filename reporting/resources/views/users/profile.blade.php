@extends('layouts.base')

@section('pageTitle', 'MY PROFILE')


@section('content')
<!-- Main content -->
<div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="profile">User profile</a>
            <!-- Form -->
            <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
                <div class="form-group mb-0">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input class="form-control" placeholder="Search" type="text">
                    </div>
                </div>
            </form>
            <!-- User -->
            <ul class="navbar-nav align-items-center d-none d-md-flex">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                <img alt="Image placeholder" src="{{Auth::user()->img}}">
                            </span>
                            <div class="media-body ml-2 d-none d-lg-block">
                                <span
                                    class="mb-0 text-sm  font-weight-bold">{{Auth::user()->firstname .' '.Auth::user()->lastname}}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome {{Auth::user()->firstname}}!</h6>
                        </div>
                        <a href="/profile" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>My profile</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="/logout" class="dropdown-item">
                            <i class="ni ni-user-run"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Header -->
    <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center"
        style="min-height: 100px; background-image: url(../images/profile-bg.png); background-size: cover; background-position: center top;">
        <!-- Mask -->
        <span class="mask bg-gradient-default opacity-8"></span>
        <!-- Header container -->
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <h1 class="display-2 text-white">Hello {{Auth::user()->firstname}}</h1>
                    <p class="text-white mt-0 mb-5">This is your profile page.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                    <img src="{{Auth::user()->img}}" class="rounded-circle">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="d-flex justify-content-between">
                            <!-- <a href="#" class="btn btn-sm btn-info mr-4">Connect</a>
                <a href="#" class="btn btn-sm btn-default float-right">Message</a> -->
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <div class="row">
                            <div class="col">
                                <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                    <!-- <div>
                                        <span class="heading">22</span>
                                        <span class="description">Friends</span>
                                    </div>
                                    <div>
                                        <span class="heading">10</span>
                                        <span class="description">Photos</span>
                                    </div>
                                    <div>
                                        <span class="heading">89</span>
                                        <span class="description">Comments</span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3>
                                {{Auth::user()->firstname .' '.Auth::user()->lastname}}<span
                                    class="font-weight-light"></span>
                            </h3>
                            <div class="h5 font-weight-300">
                                <i class="ni location_pin mr-2"></i>{{Auth::user()->username}}
                            </div>
                            <div class="h5 font-weight-300">
                                <i class="ni business_briefcase-24 mr-2"></i> {{Auth::user()->email}}
                            </div>
                            <div class="h5 font-weight-300">
                                <i class="ni business_briefcase-24 mr-2"></i> {{Auth::user()->status}}
                            </div>
                            <!-- <hr class="my-4" />
                            <p>Ryan — the name taken by Melbourne-raised, Brooklyn-based Nick Murphy — writes, performs
                                and records all of his own music.</p>
                            <a href="#">Show more</a> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow">
                    <form method="post" action="{{ route('update.profile') }}" enctype="multipart/form-data">
                    @csrf
                        <div class="card-header bg-white border-0">
                        <div id="alert-action">
                            @if (session('status'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                {{ session('status') }}
                            </div>
                            @elseif (session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                {{ session('error') }}
                            </div>
                            @endif 
                        </div> 
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">My account</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <button type="submit" class="btn btn-lg btn-primary"><i class="ni ni-bold-right"></i> Update
                                        Information</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            <h6 class="heading-small text-muted mb-4">User information</h6>
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-username">Username</label>
                                            <input type="text" id="input-username"
                                                class="form-control form-control-alternative" name="username"
                                                placeholder="Username" value="{{Auth::user()->username}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">Email address</label>
                                            <input type="email" id="input-email"
                                                class="form-control form-control-alternative" name="email"
                                                placeholder="Email" value="{{Auth::user()->email}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-first-name">First name</label>
                                            <input type="text" id="input-first-name"
                                                class="form-control form-control-alternative" name="firstname"
                                                placeholder="First name" value="{{Auth::user()->firstname}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">Last name</label>
                                            <input type="text" id="input-last-name"
                                                class="form-control form-control-alternative" name="lastname"
                                                placeholder="Last name" value="{{Auth::user()->lastname}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">Password</label>
                                            <input type="password" id="input-last-name"
                                                class="form-control form-control-alternative" name="password"
                                                placeholder="Enter New Password">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-last-name">Primary
                                                Image</label>
                                            <input type="file" id="input-last-name"
                                                class="form-control form-control-alternative" name="img" accept="image/jpeg,image/gif,image/png">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                </div>
                </form>
            </div>
        </div>

        @endsection

        @section('pagespecificscripts')


        @stop