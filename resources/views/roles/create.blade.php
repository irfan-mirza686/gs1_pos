@extends("user.layouts.layout")
@section('title', '| Create Role')

<style type="text/css">

</style>
@section("content")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        @include('user.show_flash_msgs')
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Role</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Create New</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <hr />

        <div class="container">
            <form action="{{route('role.store')}}" method="post">@csrf
                <div class="main-body">
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">

                                <div class="card-body">

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <h6 class="mb-0">Role Name</h6>
                                        </div>
                                        <div class="col-sm-9 text-secondary">
                                            <input type="text" class="form-control" name="name"
                                                value="{{old('name')}}" />
                                            @foreach($groupModule as $key => $data)
                                            <input type="hidden" name="txtModID[<?php echo $key; ?>]"
                                                value="<?php echo $data['id']; ?>">
                                            <input type="hidden" name="txtModname[<?php echo $key; ?>]"
                                                value="<?php echo $data['module_name']; ?>">
                                            <input type="hidden" name="txtModpage[<?php echo $key; ?>]"
                                                value="<?php echo $data['module_page']; ?>">
                                            @endforeach
                                        </div>
                                        <div class="col-sm-3 mt-4">
                                            <h6 class="mb-0">Roles</h6>
                                        </div>
                                        <div class="col-sm-9 mt-4">

                                            <select class="single-select" name="txtaccess[]" id="e1" multiple="multiple"
                                                required style="width: 100%;">
                                                @foreach($groupModule as $key => $data)
                                                <option value="{{$key}}">{{$data->module_name}}</option>
                                                @endforeach
                                            </select>
                                            <input type="checkbox" id="checkbox"> Select All
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success">Save</button>
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
<script src="{{asset('assets/admin/js/roles/role.js')}}"></script>
<script type="text/javascript">
    window.setTimeout(function() {
        $(".alert").alert('close');
    }, 10000);
</script>
@endpush
