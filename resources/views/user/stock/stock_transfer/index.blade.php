@extends("user.layouts.layout")

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

    <div class="page-content">
        @include('user.show_flash_msgs')
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Stock Transfer Requests</div>
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

                    <div class="ms-auto"><a href="javascript:void(0);" data-URL="{{route('save.stock.transfer.req')}}" id="addEditStockTransfer"
                            class="btn btn-primary radius-30 mt-2 mt-lg-0 addEditStockTransfer"><i class="bx bxs-plus-square"></i>Create Stock Request</a>
                    </div>
                </div>
                <div class="table-responsive">

                    <table class="table table-striped table-bordered stock-transfer-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Request #</th>
                                <th>GLN</th>
                                <th>DateTIme</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>

                        </thead>
                        <thead>
                            <tr>
                                <th></th>
                                <th><input type="text" class="form-control form-control-sm column-search rounded-0"
                                        data-column="1" placeholder="Search by Request #" /></th>
                                <!-- <th></th> -->
                                <th><input type="text" class="form-control form-control-sm column-search rounded-0"
                                        data-column="2" placeholder="Search by GLN" /></th>
                                <!-- Add more input fields for additional columns -->
                                <th></th>
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
@include('user.stock.stock_transfer.includes.create_modal')
<!--end page wrapper -->
@endsection

@push("custom-script")
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/admin/js/stock/stock_transfer.js')}}"></script>
@endpush
