<!doctype html>
<html lang="en" class="color-sidebar sidebarcolor1">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--favicon-->
    <link rel="icon" href="{{asset('assets/images/favicon-32x32.png')}}" type="image/png" />
    <!--plugins-->
    @yield("style")

    <link href="{{asset('assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
    <!-- loader-->
    <link href="{{asset('assets/css/pace.min.css')}}" rel="stylesheet" />
    <script src="{{asset('assets/js/pace.min.js')}}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/admin/css/google_fonts.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet">

    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/dark-theme.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/semi-dark.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/header-colors.css')}}" />

    <link href="{{asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/select2/css/select2-bootstrap4.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('assets/plugins/notifications/css/lobibox.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/scrollbar.css')}}" rel="stylesheet">
    <link href="{{asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
    <script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/admin/css/jquery-ui.css')}}">

    <title>{{@$pageTitle}}</title>
    <style>
    .notifyjs-corner {
      z-index: 10000 !important;
    }

    </style>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--start header -->
        @if(@$page_name!='pos')
        @include("user.layouts.header")
        <!--end header -->
        <!--navigation-->
        @include("user.layouts.sidebar")
        @endif
        <!--end navigation-->
        <!--start page wrapper -->
        @yield("content")
        @if(session()->has('notify_success'))
        <script type="text/javascript">
          $(function() {
            $.notify("{{session()->get('notify_success')}}", {
              globalPosition: 'top right',
              className: 'notify_success'
            });
          });
        </script>
        @endif
        @if(session()->has('notify_error'))
        <script type="text/javascript">
          $(function() {
            $.notify("{{session()->get('notify_error')}}", {
              globalPosition: 'top right',
              className: 'notify_error'
            });
          });
        </script>
        @endif
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        @if(@$page_name!='pos')
        @include("user.layouts.footer")
        @endif
    </div>
    <!--end wrapper-->
    <!--start switcher-->
    @if(@$page_name!='pos')
    @include("user.layouts.switcher")
    @endif
    <!--end switcher-->
    <!-- Bootstrap JS -->
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <!--plugins-->
    <!-- <script src="{{asset('assets/js/jquery.min.js')}}"></script> -->
    <script src="{{asset('assets/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>

    <script src="{{asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
    <!--app JS-->
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{asset('assets/plugins/notifications/js/lobibox.min.js')}}"></script>
    <script src="{{asset('assets/plugins/notifications/js/notifications.min.js')}}"></script>
    <script src="{{asset('assets/plugins/notifications/js/notification-custom-script.js')}}"></script>
    <!-- <script src="{{asset('assets/js/subscriber.js')}}"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="{{asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>

    <!-- <script type="text/javascript">
       window.setTimeout(function() { $(".alert").alert('close'); }, 10000);
   </script> -->
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                // buttons: [ 'copy', 'excel', 'pdf', 'print']
            });
            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>

    <!-- <script src="https://unpkg.com/feather-icons"></script> -->
    <script src="{{asset('assets/admin/js/feather.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        feather.replace()
    </script>

    </script>
    <script>
        $('.single-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.multiple-select').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
    </script>

    @stack("custom-script")
</body>

</html>
