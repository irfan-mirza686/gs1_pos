@extends("user.layouts.layout")
@section('title', '| Update Profile')

@section("style")

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
<link rel="stylesheet" href="{{asset('assets/admin/css/upload_img.css')}}">
@endsection
<style type="text/css">
    
</style>
@section("content")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
    @include('user.show_flash_msgs')
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">User</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <!-----------Update Password Modal ----------------->
        @include('user.profile.includes.update_pass_modal')

        <hr />

        <div class="container">
            <form action="{{route('profile.update')}}" method="post" enctype="multipart/form-data">@csrf
                <div class="main-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body">
                                    <!-- <input type="file" name="demoImage"> -->
                                    <div class="profile-pic-wrapper">
                                        <div class="pic-holder">
                                            <!-- uploaded pic shown here -->
                                            <?php 
                                        $image = '';
                                        if ($user->image) { 

                                            $image = asset('assets/uploads/admins/'.$user->image);
                                    }else{ 
                                            $image = asset('assets/uploads/no-image.png');
                                         
                                        }
                                    
                                       ?>
                                            <img id="profilePic" class="pic" src="<?php echo $image; ?>">

                                            <Input class="uploadProfileInput" type="file" name="profile_pic"
                                                id="newProfilePhoto" accept="image/*" style="opacity: 0;" />
                                            <label for="newProfilePhoto" class="upload-file-block">
                                                <div class="text-center">
                                                    <div class="mb-2">
                                                        <i class="fa fa-camera fa-2x"></i>
                                                    </div>
                                                    <div class="text-uppercase">
                                                        Update <br /> Profile Photo
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        </hr>

                                    </div>
                                    <div class="d-flex flex-column align-items-center text-center">

                                        <!-- <img src="assets/images/avatars/avatar-2.png" alt="Admin" class="rounded-circle p-1 bg-primary" width="110"> -->
                                        <div class="mt-3 text-left">

                                            <h4>{{Auth::user()->name}}</h4>
                                            <p class="text-secondary mb-1"><strong>Email:</strong>
                                                {{Auth::user()->email}}</p>
                                            <p class="text-secondary mb-1"><strong>Role:</strong>
                                                Super Admin</p>
                                            

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="ms-auto">
                                        <div class="col">

                                            <a href="javascript:void(0);" class="btn btn-outline-info px-5 radius-30"
                                                id="currentUserUpdatePassBtn" style="float: right;">Update Password</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <input type="hidden" name="user_id" value="{{$user->id}}">

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" class="form-control" name="name"
                                                value="{{$user->name}}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Email</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" class="form-control" name="email"
                                                value="{{$user->email}}" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Mobile</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" class="form-control" name="mobile"
                                                value="{{$user->mobile}}" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Role</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                        <select class="single-select" name="group_id" id="role">
												<option value="" disabled selected>-Select</option>
												<option value="1">Super Admin</option>
												<option value="2">Manager</option>
											</select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Status</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                        <select class="single-select" name="status" id="status">
												<option value="" disabled selected>-Select</option>
												<option value="active" {{($user->status=='active')?'selected':''}}>Active</option>
												<option value="inactive" {{($user->status=='inactive')?'selected':''}}>InActive</option>
											</select>
                                        </div>
                                    </div>
                                   
                                   
                                   
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Update Profile</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection

@push("custom-script")

<script type="text/javascript">
    $("#subscriberUpdatePassBtn").click(function() {
        $("#updateSubscriberPassModal").modal('show');
    });
</script>





<script src="{{asset('assets/admin/js/upload_img.js')}}"></script>
<script src="{{asset('assets/admin/js/current_user_changePass.js')}}"></script>
<script type="text/javascript">
       window.setTimeout(function() { $(".alert").alert('close'); }, 10000);
   </script>
@endpush