<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!--favicon-->
  <link rel="icon" href="{{asset('assets/images/favicon-32x32.png')}}" type="image/png" />
  <!-- loader-->
  <link href="{{asset('assets/css/pace.min.css')}}" rel="stylesheet" />
  <script src="{{asset('assets/js/pace.min.js')}}"></script>
  <!-- Bootstrap CSS -->
  <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
  <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet">
  <title>Login</title>
</head>

<body class="bg-login">
   <!--wrapper-->
   <div class="wrapper">
    @include('user.auth.includes.activity_modal')
    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="mb-4 text-center">
                        <img src="{{asset('uploads/logo.png')}}" width="180" alt="" />
                    </div>
                    <div class="card">
                        @if(Session::has('flash_message_error'))
                        <div class="alert alert-danger">

                           <strong> {!! session('flash_message_error') !!} </strong>
                       </div>

                       @endif
                       @if(Session::has('flash_message_success'))
                       <div class="alert alert-success">

                        <strong> {!! session('flash_message_success') !!} </strong>
                    </div>
                    @endif

                    @if(Session::has('flash_message_warning'))
                        <div class="alert alert-warning">

                           <strong> {!! session('flash_message_warning') !!} </strong>
                       </div>

                       @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="card-body">
                        <form id="checkLoginEmailForm" action="{{route('check.email')}}">

                            <div class="border p-4 rounded">
                                <div class="text-center">
                                    <h3 class="">Sign in</h3>
                                    <!-- <p>Don't have an account yet? <a href="{{ route('register') }}">Sign up here</a>
                                    </p> -->
                                </div>
                                       <!--  <div class="d-grid">
                                            <a class="btn my-4 shadow-sm btn-white" href="javascript:;"> <span class="d-flex justify-content-center align-items-center">
                                              <img class="me-2" src="{{asset('assets/images/icons/search.svg')}}" width="16" alt="Image Description">
                                              <span>Sign in with Google</span>
                                          </span>
                                      </a> <a href="javascript:;" class="btn btn-facebook"><i class="bx bxl-facebook"></i>Sign in with Facebook</a>
                                  </div> -->
                                  <div class="login-separater text-center mb-4"> <span>SIGN IN WITH EMAIL</span>
                                    <hr/>
                                </div>
                                <div class="form-body">
                                    <form class="row g-3">
                                        <div class="col-12">
                                            <label for="inputEmailAddress" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" name="email" id="inputEmailAddress" placeholder="Email Address">
                                        </div>
                                        <!-- <div class="col-12">
                                            <label for="inputChoosePassword" class="form-label">Enter Password</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input type="password" class="form-control border-end-0" id="inputChoosePassword" name="password" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                            </div>
                                        </div> -->
                                        <!-- <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="remember" id="flexSwitchCheckChecked" value="yes" checked>
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>

                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end"> <a href="{{ url('authentication-forgot-password') }}">Forgot Password ?</a>
                                            </div>
                                        </div> -->

                                        <div class="col-12 mt-2">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i><span class="loginEmailBtn">Log in</span></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
</div>
<!--end wrapper-->

<!--plugins-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="{{asset('assets/js/jquery.min.js')}}"></script> -->
<script src="{{asset('assets/admin/js/login/login.js')}}"></script>

<script>
    $(document).ready(function () {
        $("#show_hide_password a").on('click', function (event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });
    });
</script>
<script type="text/javascript">
   window.setTimeout(function() { $(".alert").alert('close'); }, 10000);
</script>
</body>

</html>
