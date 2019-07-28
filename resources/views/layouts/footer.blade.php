@if (Auth::check())
<!-- Footer -->
<footer class="footer">
    <div class="row align-items-center justify-content-xl-between">
        <div class="col-xl-6">
            <div class="copyright text-center text-xl-left text-muted">
                &copy; <?php echo date('Y');?> <a href="https://www.creative-tim.com" class="font-weight-bold ml-1"
                    target="_blank">OptiAuto</a>
            </div>
        </div>
        <!-- <div class="col-xl-6">
            <ul class="nav nav-footer justify-content-center justify-content-xl-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
              </li>
              <li class="nav-item">
                <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
              </li>
              <li class="nav-item">
                <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
              </li>
            </ul>
          </div> -->
    </div>
</footer>
</div>
</div>
<!-- Argon Scripts -->
@else
<!-- Footer -->
<footer class="py-5">
    <div class="container">
        <div class="row align-items-center justify-content-xl-between">
            <div class="col-xl-6">
                <div class="copyright text-center text-xl-left text-muted">
                    &copy; <?php echo date('Y');?> <a href="" class="font-weight-bold ml-1" target="_blank">OptiAuto</a>
                </div>
            </div>
            <!-- <div class="col-xl-6">
            <ul class="nav nav-footer justify-content-center justify-content-xl-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
              </li>
              <li class="nav-item">
                <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
              </li>
              <li class="nav-item">
                <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
              </li>
            </ul>
          </div> -->
        </div>
    </div>
</footer>
<!-- Argon Scripts -->
@endif

<!-- Core -->
<script src="/assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/vendor/js-cookie/js.cookie.js"></script>



@yield('pagespecificscripts')

<!-- Argon JS -->
<script src="/assets/js/argon.js?v=1.0.0"></script>

<script src="/assets/js/demo.min.js"></script>

<script>
$(document).ready(function() {
    var $div2 = $('#alert-action');
    setTimeout(function() {
        $div2.hide();
    }, 3000);
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    if (window.location.href.indexOf("summary") > -1) {
        $('#sidenav-collapse-main .navbar-nav li:nth-child(1) a').removeClass("text-primary");
        $('#sidenav-collapse-main .navbar-nav li:nth-child(1) a').addClass("text-green");
    } else if (window.location.href.indexOf("zing-credit") > -1) {
        $('#sidenav-collapse-main .navbar-nav li:nth-child(4) a').removeClass("text-primary");
        $('#sidenav-collapse-main .navbar-nav li:nth-child(4) a').addClass("text-green");
    } else if (window.location.href.indexOf("clients") > -1) {
        $('#sidenav-collapse-main .navbar-nav li:nth-child(5) a').removeClass("text-primary");
        $('#sidenav-collapse-main .navbar-nav li:nth-child(5) a').addClass("text-green");
    }
});
</script>

<script>
$(document).ready(function() {
    $('#datatable-basic').DataTable({
        destroy: true,
        dom: 'Blfrtip',
        language: {
            paginate: {
                next: '&#8594;', // or '→'
                previous: '&#8592;' // or '←' 
            }
        },

        buttons: [{
                extend: 'colvis',
                text: 'Select Column'
            },
            {
                extend: 'csv',
                text: 'Export CSV',
                exportOptions: {
                    columns: ':visible',
                    text: 'Export CSV',
                    className: 'btn-space'
                }
            }

        ]

    });
});

$(document).ready(function() {
    var $div2 = $('#alert-action');
    setTimeout(function() {
        $div2.hide();
    }, 3000);
});
</script>




</body>

</html>