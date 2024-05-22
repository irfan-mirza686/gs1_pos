@extends("user.layouts.layout")
@section('title', '| Customers')

@section("content")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">

    @include('user.sales.pos.create_customer_modal')
    @include('user.customers.includes.import')
    @include('user.customers.includes.view_addresses')

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Customers</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i
                                    class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <!-- <h6 class="mb-0 text-uppercase">DataTable Import</h6> -->
        <hr />
        <div class="card">
            <div class="card-header">
                <div class="ms-auto">
                    <div class="col">
                    <a href="javascript:void(0);" class="btn btn-danger px-5 rounded-0"id="importCustomers"
                            ><i class="fadeIn animated bx bx-import"></i> Import Customers</a>
                        <a href="javascripti:void(0)" class="btn btn-success px-5 rounded-0 add" id="addNewCustomer"
                            style="float: right;"><i class="fadeIn animated bx bx-plus-circle"></i> Add New</a>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered customers-table">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Name</th>
                                <th width="10%">Mobile</th>
                                <th width="10%">Vat #</th>
                                <th width="10%">Address</th>
                                <th width="10%">Status</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--end page wrapper -->
@endsection

@push('custom-script')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsEUxB9psxb-LxhYx8hJtF248gj4bx49A"></script>
<script src="{{asset('assets/admin/js/sales/drag_google_mark.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/admin/js/customer/customer_script.js')}}"></script>
@endpush
