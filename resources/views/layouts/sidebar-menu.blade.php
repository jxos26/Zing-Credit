<!-- Sidenav -->
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      @if(Auth::user()->type == "ADMIN")
          <a class="navbar-brand pt-0" href="/admin/summary" class="dropdown-item">
      @else
          <a class="navbar-brand pt-0" href="/summary" class="dropdown-item">
      @endif
        <img src="/images/logo.png" class="navbar-brand-img" alt="...">
      </a>
      <!-- User -->
      <ul class="nav align-items-center d-md-none">
        <li class="nav-item dropdown">
          <!-- <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div> -->
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="/{{Auth::user()->img}}">
              </span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome {{Auth::user()->firstname .' '.Auth::user()->lastname}}!</h6>
            </div>
            @if(Auth::user()->type == "ADMIN")
                <a href="/admin/profile" class="dropdown-item">
            @else
                <a href="/profile" class="dropdown-item">
            @endif
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </a>            
            <div class="dropdown-divider"></div>
            @if(Auth::user()->type == "ADMIN")
                <a href="/admin/logout" class="dropdown-item">
            @else
                <a href="/logout" class="dropdown-item">
            @endif
              <i class="ni ni-user-run"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>
      </ul>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="/summary">
                <img src="/images/logo.png">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <!-- <li class="nav-item">
            @if(Auth::user()->type == "ADMIN")
                <a class="nav-link text-primary" href="/admin/summary" class="dropdown-item">
            @else
                <a class="nav-link text-primary" href="/summary" class="dropdown-item">
            @endif
              <i class="ni ni-calendar-grid-58 "></i> Summary
            </a>
          </li> -->
          <!-- <li class="nav-item">
            <a class="nav-link  text-primary" href="">
              <i class="ni ni-planet"></i> Call Tracking
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  text-primary" href="">
              <i class="ni ni-sound-wave"></i> Website Analytics
            </a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link  text-primary" href="/zing-credit" class="dropdown-item">
              <i class="ni ni-credit-card"></i> Zing Credit
            </a>            
          </li>
          @if(Auth::user()->type == "ADMIN")
          <li class="nav-item">           
                <a class="nav-link  text-primary" href="/users" class="dropdown-item">          
           
              <i class="ni ni-single-02"></i> Users
            </a>            
          </li>
          @endif
          <!-- <li class="nav-item">
            <a class="nav-link  text-primary" href="">
              <i class="ni ni-bullet-list-67"></i> Paid Search
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  text-primary" href="">
              <i class="ni ni-square-pin"></i> SEO
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  text-primary" href="">
              <i class="ni ni-email-83"></i> Direct Mail
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  text-primary" href="">
              <i class="ni ni-collection"></i> RVM
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  text-primary" href="">
              <i class="ni ni-curved-next"></i> Reputation Management
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  text-primary" href="">
              <i class="ni ni-notification-70"></i> Social Media
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  text-primary" href="">
              <i class="ni ni-align-center"></i> Marketing Budget
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link  text-primary" href="">
              <i class="ni ni-briefcase-24"></i> Billing
            </a>
          </li> -->
        </ul>
        
      </div>
    </div>
  </nav>