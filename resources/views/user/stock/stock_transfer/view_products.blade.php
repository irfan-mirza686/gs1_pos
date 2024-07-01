@extends("user.layouts.layout")

<style>
    div .productCard {
        width: 300px;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .card-body {
        position: relative;
        text-align: center;
        padding: 20px;
    }

    .discount-badge {
        position: absolute;
        top: 80px;
        left: 80px;
        background: #5cb85c;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9em;
    }

    .card-title {
        font-size: 1.2em;
        font-weight: bold;
        margin: 10px 0;
    }

    .price {
        font-size: 1.1em;
        color: #d9534f;
        font-weight: bold;
    }

    .original-price {
        text-decoration: line-through;
        color: grey;
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
            <div class="breadcrumb-title pe-3">Stock Transfer Request</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">View Products</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <hr />

        <div class="card">
            <div class="card-header">
                <table class="table table-sm table-stripped" style="border: none;">
                    <tr>
                        <td>
                            Request #: {{$stock_transfer->request_no}}
                        </td>
                        <td class="text-end">
                            Status: {{strtoupper($stock_transfer->status)}}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-body">
                <div class="container my-5">
                    <div class="row">
                        @foreach($productInfo as $item)
                        <div class="col-md-4 d-flex justify-content-center">
                            <div class="card productCard">
                                <img src="{{$item['front_image']}}" class="card-img-top"
                                    alt="{{$item['productnameenglish']}}">
                                <div class="card-body mb-4">
                                    <div class="discount-badge" style="width: 150px;"><a href="javascript:void(0);"
                                            class="viewProductInfo" data-ProductInfo="{{json_encode($item)}}"
                                            style="cursor: pointer; text-decoration: none;">{{$item['barcode']}}</a>
                                    </div>
                                    <h5 class="card-title">{{$item['productnameenglish']}}</h5>

                                </div><br>
                                <div class="left-badge badge bg-primary mt-2">Request Qty: {{$item['req_quantity']}}
                                </div>
                                <div class="right-badge badge bg-secondary mt-2">Received Qty:
                                    {{$item['receive_quantity']}}</div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@include('user.stock.stock_transfer.includes.product_info_modal')
<!--end page wrapper -->
@endsection

@push("custom-script")
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/admin/js/stock/stock_transfer.js')}}"></script>
@endpush
