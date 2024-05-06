<!doctype html>
    <html lang="en">

    <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!--favicon-->
      <link rel="icon" href="{{asset('assets/images/favicon-32x32.png')}}" type="image/png" />
      <!-- loader-->
      <link href="{{asset('assets/css/pace.min.css')}}" rel="stylesheet" />
      <script src="{{asset('assets/js/pace.min.js') }}"></script>
      <!-- Bootstrap CSS -->
      <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
      <link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
      <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet">
      <link href="{{asset('assets/css/scrollbar.css')}}" rel="stylesheet">

      <link href="{{asset('assets/plugins/datetimepicker/css/classic.css')}}" rel="stylesheet" />
      <link href="{{asset('assets/plugins/datetimepicker/css/classic.time.css')}}" rel="stylesheet" />
      <link href="{{asset('assets/plugins/datetimepicker/css/classic.date.css')}}" rel="stylesheet" />
      <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css')}}">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <title>Subscriber | Registration</title>
      <style type="text/css">
    #saleLoader {
        display: none;
        margin: 0;
        position: absolute;
        top: 29%;
        left: 90%;
        margin-right: -50%;
        transform: translate(-50%, -50%);
    }
</style>
  </head>

  <body class="bg-login">
    <!--wrapper-->
    <div class="wrapper">
        <div class="d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container">
               
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">
                    <div class="col mx-auto">
                        <div class="my-4 text-center">
                            <img src="{{asset('uploads/logo.png')}}" width="180" alt="" />
                        </div>
                        <div class="card">
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
                            <div class="border p-4 rounded">
                                <div class="text-center">
                                    <h3 class="">Sign Up</h3>
                                    <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a>
                                    </p>
                                </div>
                                <!--     <div class="d-grid">
                                        <a class="btn my-4 shadow-sm btn-white" href="javascript:;"> <span class="d-flex justify-content-center align-items-center">
                                          <img class="me-2" src="assets/images/icons/search.svg" width="16" alt="Image Description">
                                          <span>Sign Up with Google</span>
                                      </span>
                                  </a> <a href="javascript:;" class="btn btn-facebook"><i class="bx bxl-facebook"></i>Sign Up with Facebook</a>
                              </div> -->
                              <div class="login-separater text-center mb-4"> <span>SIGN UP</span>
                                <hr/>
                            </div>
                            <div class="form-body">
                               
                                
                                <form class="row g-3" method="POST" action="{{ route('register') }}">@csrf
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="no" id="gcpValue">
                                        <label class="form-check-label" for="gcpValue">Do you have the GS1 GCP?</label>
                                    </div>
                                    <div class="col-sm-12" style="display: none;" id="gs1Input">
                                        <label for="gs1gcp" class="form-label">GCP Number</label>
                                        <input type="text" name="gs1_gcp" class="form-control" id="gs1gcp" placeholder="Enter GCP Number">
                                        <input type="hidden" name="barcodeLimit" id="barcodeLimit">
                                        <input type="hidden" name="licensee_gln" id="licensee_gln">
                                        <div id="saleLoader">
                              
                                <img src="{{asset('uploads/search.gif')}}" style="height: 30px; widows: 30px;">
                            
                            </div>
                                        <span id="showGCP"></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="inputFirstName" class="form-label">Full Name</label>
                                        <input type="text" name="name" class="form-control" id="inputFirstName" placeholder="Full Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="inputFirstName" class="form-label">Company Name</label>
                                        <input type="text" name="company_name" class="form-control" id="inputFirstName" placeholder="Company Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="inputLastName" class="form-label">Mobile</label>
                                        <input type="text" name="mobile" class="form-control" id="inputLastName" placeholder="Enter Mobile Number">
                                    </div>
                                    <div class="col-12">
                                        <label for="inputEmailAddress" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email" id="inputEmailAddress" placeholder="example@user.com">
                                    </div>
                                    <div class="col-12">
                                        <label for="inputChoosePassword" class="form-label">Password</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" class="form-control border-end-0" id="inputChoosePassword" name="password" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                                        <div class="input-group" id="show_hide_confrimpassword">
                                            <input type="password" class="form-control border-end-0" id="inputConfirmPassword" name="password_confirmation" placeholder="Enter Confirm Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="text" class="form-control date" id="start_date" placeholder="Select Start Date" name="start_date">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="text" name="end_date" class="form-control date" id="end_date" placeholder="Select End Date">
                                    </div>
                                    <div class="col-12">
                                        <label for="inputAddress" class="form-label">Company Address</label>
                                        <textarea class="form-control" id="inputAddress4" rows="3" placeholder="Address" name="company_address"></textarea>
                                    </div>
                                    
                                    <!-- <div class="col-12">
                                        <label for="inputSelectCountry" class="form-label">Country</label>
                                        <select class="form-select" id="inputSelectCountry" aria-label="Default select example">
                                            <option selected>India</option>
                                            <option value="1">United Kingdom</option>
                                            <option value="2">America</option>
                                            <option value="3">Dubai</option>
                                        </select>
                                    </div> -->
                                   <!--  <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                                            <label class="form-check-label" for="flexSwitchCheckChecked">I read and agree to Terms & Conditions</label>
                                        </div>
                                    </div> -->
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" id="singupBtn" class="btn btn-primary"><i class='bx bx-user'></i>Sign up</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
</div>
<!--end wrapper-->
<!-- Bootstrap JS -->
<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<!--plugins-->
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<script src="{{asset('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
<script src="{{asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>

<script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict'

              // Fetch all the forms we want to apply custom Bootstrap validation styles to
              var forms = document.querySelectorAll('.needs-validation')

              // Loop over them and prevent submission
              Array.prototype.slice.call(forms)
              .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
          })()
      </script>
      <script src="{{asset('assets/plugins/datetimepicker/js/legacy.js')}}"></script>
      <script src="{{asset('assets/plugins/datetimepicker/js/picker.js')}}"></script>
      <script src="{{asset('assets/plugins/datetimepicker/js/picker.time.js')}}"></script>
      <script src="{{asset('assets/plugins/datetimepicker/js/picker.date.js')}}"></script>
      <script src="{{asset('assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js')}}"></script>
      <script src="{{asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js')}}"></script>
      
      <script>
        $('.datepicker').pickadate({
            selectMonths: true,
            selectYears: true
        }),
        $('.timepicker').pickatime()
    </script>
    <script>
        $(function () {
            $('#date-time').bootstrapMaterialDatePicker({
                format: 'YYYY-MM-DD HH:mm'
            });
            $('.date').bootstrapMaterialDatePicker({
                time: false
            });
            $('#time').bootstrapMaterialDatePicker({
                date: false,
                format: 'HH:mm'
            });
        });
    </script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function () {
            $("#gcpValue").on('click',function(){
                let gcpVal = $(this).val();
                if (gcpVal=='no') {
                    $("#gs1Input").show();

                    $("#gcpValue").val('yes');
                }else{
                     $("#singupBtn").prop("disabled", false);
                    $("#gs1Input").hide();
                    $("#gcpValue").val('no');
                }
            });

            $("#gs1gcp").keyup(function(){
                var gs1gcp = $("#gs1gcp").val();

                $.ajax({
                    type: 'post',
                    url: '/check-gs1-gcp',
                    data:{
                        _token:"{{ csrf_token() }}",
                        gs1gcp:gs1gcp
                },beforeSend:function(){
                    $("#saleLoader").show();
                },
                    success:function(resp){
                        console.log(resp);
                        // return false;
                        setTimeout(function() {
                            if(resp=="false"){
                                $("#singupBtn").prop("disabled", true);
                                $("#showGCP").html("<font color=red>GCP Not Available</font>");
                            }else if(resp.status==200){
                                $("#singupBtn").prop("disabled", false);
                                $("#showGCP").html("<font color=green>GCP Available</font>");
                                $("#barcodeLimit").val(resp.gcpResponse.barcodeLimit);
                                $("#licensee_gln").val(resp.gcpResponse.licensee_gln);
                            }
                        }, 2000);
                        $("#saleLoader").hide();
                    },error:function(){
                        alert("Error")
                    }
                });
            });
            
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

            $("#show_hide_confrimpassword a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_confrimpassword input').attr("type") == "text") {
                    $('#show_hide_confrimpassword input').attr('type', 'password');
                    $('#show_hide_confrimpassword i').addClass("bx-hide");
                    $('#show_hide_confrimpassword i').removeClass("bx-show");
                } else if ($('#show_hide_confrimpassword input').attr("type") == "password") {
                    $('#show_hide_confrimpassword input').attr('type', 'text');
                    $('#show_hide_confrimpassword i').removeClass("bx-hide");
                    $('#show_hide_confrimpassword i').addClass("bx-show");
                }
            });
        });
    </script>
    <script type="text/javascript">
       window.setTimeout(function() { $(".alert").alert('close'); }, 10000);
   </script>
   <!--app JS-->
   <script src="{{asset('assets/js/app.js')}}"></script>
</body>

</html>
