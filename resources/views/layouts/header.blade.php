<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>@yield('pageTitle') | AutoOptic</title>
    <!-- Favicon -->
    <link href="/images/icon.png" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="/assets/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    @yield('pagespecificstylesheet')
    <!-- Argon CSS -->
    <link type="text/css" href="/assets/css/argon.css?v=1.0.0" rel="stylesheet">
    <link type="text/css" href="/assets/css/custom.css" rel="stylesheet">

    

</head>


@if (Auth::check())
  @include('layouts.sidebar-menu')   
  <body data-token="{{ csrf_token() }}">
@else
  <body class="bg-default" data-token="{{ csrf_token() }}">
@endif

    