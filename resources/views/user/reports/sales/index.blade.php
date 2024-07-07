@extends("user.layouts.layout")
@section('title', '| Sales')

@section("style")
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
<style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
@endsection

@section("content")
<!--start page wrapper -->
<div class="page-wrapper">

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Report</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Sales</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <hr />

        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <div class="mb-3 col-md-4">
                        <label for="productNameEn" class="form-label">Customer Type <font style="color: red;">*</font>
                        </label>
                        <select class="single-select form-control" name="customerType" id="customerType">
                            <option disabled selected>-select-</option>
                            <!-- <option value="walkin">Walk-in Customer</option> -->
                            @foreach ($customers as $customer)
                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3 col-md-4">
                        <label for="productNameEn" class="form-label">Date <font style="color: red;">*</font></label>
                        <input type="text" class="form-control datepicker flatpickr-input active" id="startDate"
                            readonly="readonly">

                    </div>

                    <!-- <div class="form-group row"> -->
                        <div class="mb-3 col-md-4">
                            <label for="productNameEn" class="form-label">GPC Type <font style="color: red;">*
                                </font>
                            </label>
                            <select class="single-select form-control appendGpc" name="gcp" id="appendGpc" multiple>



                            </select>

                        </div>

                    <!-- </div> -->
                </div>
                <div class="card-footer">
                    <button type="button" id="show_report" data-Route="{{route('report.sales.data')}}"
                        class="btn btn-info showUserSaleBtn">Show Report</button>

                </div>
            </div>

            <div class="card salesList">
                <div class="card-body">
                    <div class="loader" id="loader"></div>
                    <canvas id="myChart" width="400" height="200"></canvas>
                </div>

            </div>
        </div>
    </div>
    <!--end page wrapper -->
    @endsection

    @push("custom-script")
    @include('user.reports.sales.scripts.sales_report')

    @endpush
