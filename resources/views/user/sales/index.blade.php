@extends("user.layouts.layout")
@section('title', '| Sales')

@section("content")
<!--start page wrapper -->
<div class="page-wrapper">
@include('user.product.includes.create_product_modal')

    <div class="page-content">
        @include('user.show_flash_msgs')
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Sales</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <hr />

        <div class="card">
            <div class="card-body">
                <div class="d-lg-flex align-items-center mb-4 gap-3">

                    <div class="ms-auto"><a href="{{route('pos')}}"
                            class="btn btn-primary radius-30 mt-2 mt-lg-0" target="_blank"><i class="bx bxs-plus-square"></i>Add New</a>
                    </div>
                </div>
                <div class="table-responsive">
                <table class="table table-striped table-bordered sales-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order#</th>
                                <th>Customer</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection

@push("custom-script")
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/admin/js/sales/sales.js')}}"></script>
@endpush
