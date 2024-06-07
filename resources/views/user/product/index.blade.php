@extends("user.layouts.layout")
@section('title', '| Products')

<style>
    .overlay {
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(255, 255, 255, 0.8) url('{{ asset("assets/uploads/loader1.gif")}}') center no-repeat;
    }

    /* Turn off scrollbar when body element has the loading class */
    body.loading {
        overflow: hidden;
    }

    /* Make spinner image visible when body element has the loading class */
    body.loading .overlay {
        display: block;
    }
</style>

@section("content")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="overlay"></div>
    @include('user.product.includes.create_product_modal')
    @include('user.product.includes.select_prod_type_modal')

    <div class="page-content">
        @include('user.show_flash_msgs')
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Products</div>
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

                    <div class="ms-auto"><a href="javascript:void(0);" data-URL="{{route('product.create')}}" id="addNewProductBtn"
                            class="btn btn-primary radius-30 mt-2 mt-lg-0 add"><i class="bx bxs-plus-square"></i>Add
                            New</a>
                            <a href="javascript:void(0);" data-URL="{{route('sync.products')}}" id="syncProductsBtn"
                            class="btn btn-primary radius-30 mt-2 mt-lg-0 add"><i class="fadeIn animated bx bx-sync"></i>Sync Products</a>
                    </div>


                </div>
                <div class="table-responsive">

                    <table class="table table-striped table-bordered products-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Image</th>
                                <th>Product Name EN</th>
                                <th>Product Name AR</th>
                                <th>Brand EN</th>
                                <th>Barcode</th>
                                <th class="text-center">Action</th>
                            </tr>

                        </thead>
                        <thead>
                        <tr>
                                <th></th>
                                <th><input type="text" class="form-control form-control-sm column-search rounded-0"
                                        data-column="1" placeholder="Search by Product Type" /></th>
                                        <th></th>
                                <th><input type="text" class="form-control form-control-sm column-search rounded-0"
                                        data-column="2" placeholder="Search by Product Name EN" /></th>
                                <th><input type="text" class="form-control form-control-sm column-search rounded-0"
                                        data-column="3" placeholder="Search by Product Name AR" /></th>
                                <th><input type="text" class="form-control form-control-sm column-search rounded-0"
                                        data-column="4" placeholder="Search by Brand EN" /></th>

                                <th><input type="text" class="form-control form-control-sm column-search rounded-0"
                                        data-column="5" placeholder="Search by Barcode" /></th>
                                <!-- Add more input fields for additional columns -->
                                <th></th>
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
@include('user.product.includes.update_itemPrice_modal')
<!--end page wrapper -->
@endsection

@push("custom-script")
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/admin/js/product/product_script.js')}}"></script>
<script src="{{asset('assets/admin/js/product/product_items.js')}}"></script>
@endpush
