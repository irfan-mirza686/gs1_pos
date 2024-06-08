@extends("user.layouts.layout")


@section("style")
<link href="{{asset('assets/admin/css/bs-stepper.css')}}" rel="stylesheet" />
@endsection

@section("content")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="overlay"></div>

    <div class="page-content">
    <style>
        .step-header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        .step-header .nav-item {
            flex: 1;
        }
        .nav-link {
            font-size: 1.2em;
            padding: 10px;
            color: #333;
            background-color: #e9ecef;
            text-align: center;
            border: none;
            cursor: not-allowed;
            position: relative;
        }
        .nav-link.active {
            color: #fff;
            background-color: #17a2b8;
        }
        .nav-link:after {
            content: '';
            position: absolute;
            right: -20px;
            top: 0;
            width: 0;
            height: 100%;
            border-left: 20px solid #e9ecef;
            border-top: 20px solid transparent;
            border-bottom: 20px solid transparent;
            z-index: 1;
        }
        .nav-link.active:after {
            border-left-color: #17a2b8;
        }
        .nav-link:last-child:after {
            display: none;
        }
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .step-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .step-container h3 {
            margin-bottom: 20px;
        }
        .btn-next, .btn-prev {
            margin-top: 20px;
        }
        .card-text {
            flex: 1;
        }
        .btn-icon {
            display: inline-block;
            width: 100%;
            height: 100px;
            background-color: #f8f9fa;
            border: none;
            text-align: center;
            vertical-align: middle;
            line-height: 100px;
        }
        .btn-icon img {
            max-height: 50px;
        }
        .icon-title {
            text-align: center;
            margin-top: 5px;
        }
        .modal-body .row.text-center {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .modal-body .row.mb-4 {
            display: flex;
            justify-content: space-around;
        }
        .modal-body .row.mb-4 .col {
            text-align: center;
        }


    </style>

<div class="container mt-5">
    <div id="stepper1" class="bs-stepper linear">
        <div class="card">

            <div class="card-header">
                <div class="d-lg-flex flex-lg-row align-items-lg-center justify-content-lg-between" role="tablist">
                    <div class="step active" data-target="#test-l-1">
                        <div class="step-trigger" role="tab" id="stepper1trigger1" aria-controls="test-l-1"
                            aria-selected="true">
                            <div class="bs-stepper-circle">1</div>
                            <!-- <div class="">
                                <h5 class="mb-0 steper-title">Personal Info</h5>
                                <p class="mb-0 steper-sub-title">Enter Your Details</p>
                            </div> -->
                        </div>
                    </div>
                    <div class="bs-stepper-line"></div>
                    <div class="step" data-target="#test-l-2">
                        <div class="step-trigger" role="tab" id="stepper1trigger2" aria-controls="test-l-2"
                            aria-selected="false" disabled="disabled">
                            <div class="bs-stepper-circle">2</div>
                            <!-- <div class="">
                                <h5 class="mb-0 steper-title">Account Details</h5>
                                <p class="mb-0 steper-sub-title">Setup Account Details</p>
                            </div> -->

                        </div>
                    </div>
                    <div class="bs-stepper-line"></div>
                    <div class="step" data-target="#test-l-3">
                        <div class="step-trigger" role="tab" id="stepper1trigger3" aria-controls="test-l-3"
                            aria-selected="false" disabled="disabled">
                            <div class="bs-stepper-circle">3</div>
                            <div class="">
                                <!-- <h5 class="mb-0 steper-title">Education</h5> -->
                                <!-- <p class="mb-0 steper-sub-title">Education Details</p> -->
                            </div>
                        </div>
                    </div>
                    <!-- <div class="bs-stepper-line"></div>
                    <div class="step" data-target="#test-l-4">
                        <div class="step-trigger" role="tab" id="stepper1trigger4" aria-controls="test-l-4"
                            aria-selected="false" disabled="disabled">
                            <div class="bs-stepper-circle">3</div>
                            <div class="">
                                <h5 class="mb-0 steper-title">Work Experience</h5>
                                <p class="mb-0 steper-sub-title">Experience Details</p>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="card-body">

                <div class="bs-stepper-content">
                    <!-- <form onsubmit="return false"> -->
                        <div id="test-l-1" role="tabpanel" class="bs-stepper-pane active dstepper-block"
                            aria-labelledby="stepper1trigger1">
                            <h5 class="mb-1">Filter Products</h5>
                            <!-- <p class="mb-4">Enter your personal information to get closer to copanies</p> -->

                            <div class="row g-3">
                                <input type="text" id="search" placeholder="Search Product" class="form-control mb-3">
                                <div id="product-list" class="row">
                                    <!-- Product cards will be appended here dynamically -->
                                </div>
                                <div class="col-12 col-lg-6">
                                    <button class="btn btn-primary px-4" onclick="stepper1.next()">Next<i
                                            class="bx bx-right-arrow-alt ms-2"></i></button>
                                </div>
                            </div>
                            <!---end row-->

                        </div>

                        <div id="test-l-2" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger2">

                            <h5 class="mb-1">Cart Products</h5>
                            <!-- <p class="mb-4">Enter Your Account Details.</p> -->

                            <div class="row g-3 mt-2">
                                <div id="selected-products" class="row">
                                    <!-- Selected products will be displayed here -->
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3">
                                        <button class="btn btn-outline-secondary px-4" onclick="stepper1.previous()"><i
                                                class="bx bx-left-arrow-alt me-2"></i>Previous</button>
                                        <button class="btn btn-primary px-4" onclick="stepper1.next()">Next<i
                                                class="bx bx-right-arrow-alt ms-2"></i></button>
                                    </div>
                                </div>
                            </div>
                            <!---end row-->

                        </div>

                        <div id="test-l-3" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger3">
                            <h5 class="mb-1">Request Information</h5>
                            <!-- <p class="mb-4">Inform companies about your education life</p> -->

                            <div class="row g-3">
                                <form id="request-form">
                                <input type="hidden" id="baseURL" value="{{url('/')}}">
                                    <div class="mb-3">
                                        <label for="request_no" class="form-label">Request #</label>
                                        <input type="text" class="form-control" id="request_no" name="request_no"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gln_from" class="form-label">GLN From</label>
                                        <select class="form-select" id="gln_from" name="gln_from">
                                            <option value="">-select-</option>
                                            @foreach($glnName as $gln)
                                            <option value="{{$gln}}">{{$gln}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gln_to" class="form-label">GLN To</label>
                                        <select class="form-select" id="gln_to" name="gln_to">
                                            <option value="">-select-</option>
                                            @foreach($glnName as $gln)
                                            <option value="{{$gln}}">{{$gln}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </form>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3">
                                        <button class="btn btn-outline-secondary px-4" onclick="stepper1.previous()"><i
                                                class="bx bx-left-arrow-alt me-2"></i>Previous</button>
                                        <!-- <button class="btn btn-primary px-4" onclick="stepper1.next()">Next<i
                                                class="bx bx-right-arrow-alt ms-2"></i></button> -->
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- <div id="test-l-4" role="tabpanel" class="bs-stepper-pane" aria-labelledby="stepper1trigger4">
                            <h5 class="mb-1">Request Information</h5>
                            <p class="mb-4">Can you talk about your past work experience?</p>

                            <div class="row g-3">

                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3">
                                        <button class="btn btn-primary px-4" onclick="stepper1.previous()"><i
                                                class="bx bx-left-arrow-alt me-2"></i>Previous</button>
                                        <button class="btn btn-success px-4" onclick="stepper1.next()">Submit</button>
                                    </div>
                                </div>

                            </div>


                        </div> -->
                    <!-- </form> -->
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Product Details Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Digital Links</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row text-center mb-4">
                    <div class="col-md-2 d-flex flex-column align-items-center">
                        <button class="btn btn-icon" data-category="Safety Information"><img
                                src="{{ asset('assets/uploads/digital_links/safetyinformation.png') }}"
                                alt="Safety Information"></button>
                        <div class="icon-title">Safety Information</div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center">
                        <button class="btn btn-icon" data-category="electricity_company"><img
                                src="{{ asset('assets/uploads/digital_links/sec.jpeg') }}"
                                alt="Saudi Electricity Company"></button>
                        <div class="icon-title">Saudi Electricity Company</div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center">
                        <button class="btn btn-icon" data-category="Product Contents"><img
                                src="{{ asset('assets/uploads/digital_links/productcontents.png') }}"
                                alt="Product Contents"></button>
                        <div class="icon-title">Product Contents</div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center">
                        <button class="btn btn-icon" data-category="Controlled Serials"><img
                                src="{{ asset('assets/uploads/digital_links/productlocation.png') }}"
                                alt="Controlled Serials"></button>
                        <div class="icon-title">Controlled Serials</div>
                    </div>
                </div>
                <div class="row text-center mb-4">
                    <div class="col-md-2 d-flex flex-column align-items-center">
                        <button class="btn btn-icon" data-category="Product Recall"><img
                                src="{{ asset('assets/uploads/digital_links/productrecall.png') }}"
                                alt="Product Recall"></button>
                        <div class="icon-title">Product Recall</div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center">
                        <button class="btn btn-icon" data-category="Recipe"><img
                                src="{{ asset('assets/uploads/digital_links/recipe.png') }}" alt="Recipe"></button>
                        <div class="icon-title">Recipe</div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center">
                        <button class="btn btn-icon" data-category="Packaging Composition"><img
                                src="{{ asset('assets/uploads/digital_links/packaging.png') }}"
                                alt="Packaging"></button>
                        <div class="icon-title">Packaging</div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center">
                        <button class="btn btn-icon" data-category="Electronic Leaflets"><img
                                src="{{ asset('assets/uploads/digital_links/electronicleaflets.png') }}"
                                alt="Electronic Leaflets"></button>
                        <div class="icon-title">Electronic Leaflets</div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col text-center"><strong>Product Name:</strong> <span id="modalProductName"></span>
                    </div>
                    <div class="col text-center"><strong>Brand Name:</strong> <span id="modalBrandName"></span></div>
                    <div class="col text-center"><strong>Barcode:</strong> <span id="modalBarcode"></span></div>
                    <div class="col text-center"><button class="btn btn-primary">Add Digital Link</button></div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="data-table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Actions</th>
                                    <th>Target URL</th>
                                    <th>Digital Information Type</th>
                                    <th>GTIN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be dynamically populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push("custom-script")
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="{{asset('assets/admin/js/bs-stepper.min.js')}}"></script>
<script src="{{asset('assets/admin/js/main-stepper.js')}}"></script>
<script src="{{asset('assets/admin/js/stock/stock_transfer_req.js')}}"></script>
@endpush
