@extends("user.layouts.layout")

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
                cursor: pointer;
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .card-body {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .card.selected {
                border: 2px solid #007bff;
                box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
            }

            .step-container {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .step-container h3 {
                margin-bottom: 20px;
            }

            .btn-next,
            .btn-prev {
                margin-top: 20px;
            }

            .card-text {
                flex: 1;
            }
        </style>
        </head>

        <body>
            <div class="container mt-5">
                <div id="step-form">
                    <div class="step-header">
                        <nav class="nav nav-tabs">
                            <div class="nav-item">
                                <a class="nav-link active" id="step1-tab" data-bs-toggle="tab" data-bs-target="#step1"
                                    type="button" role="tab" aria-controls="step1" aria-selected="true">Step 1</a>
                            </div>
                            <div class="nav-item">
                                <a class="nav-link" id="step2-tab" data-bs-toggle="tab" data-bs-target="#step2"
                                    type="button" role="tab" aria-controls="step2" aria-selected="false">Step 2</a>
                            </div>
                            <div class="nav-item">
                                <a class="nav-link" id="step3-tab" data-bs-toggle="tab" data-bs-target="#step3"
                                    type="button" role="tab" aria-controls="step3" aria-selected="false">Step 3</a>
                            </div>
                        </nav>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active step-container" id="step1" role="tabpanel"
                            aria-labelledby="step1-tab">
                            <h3>Step 1: Search Products</h3>
                            <input type="text" id="search" placeholder="Search Product" class="form-control mb-3">
                            <div id="product-list" class="row">
                                <!-- Product cards will be appended here dynamically -->
                            </div>
                            <button class="btn btn-primary btn-next" id="go-to-step2">Next</button>
                        </div>
                        <div class="tab-pane fade step-container" id="step2" role="tabpanel"
                            aria-labelledby="step2-tab">
                            <h3>Step 2: Selected Products</h3>
                            <div id="selected-products" class="row">
                                <!-- Selected products will be displayed here -->
                            </div>
                            <button class="btn btn-secondary btn-prev" id="back-to-step1">Previous</button>
                            <button class="btn btn-primary btn-next" id="go-to-step3">Next</button>
                        </div>
                        <div class="tab-pane fade step-container" id="step3" role="tabpanel"
                            aria-labelledby="step3-tab">
                            <h3>Step 3: Order Information</h3>
                            <p>Order details go here...</p>
                            <button class="btn btn-secondary btn-prev" id="back-to-step2">Previous</button>
                            <button class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection

@push("custom-script")
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/admin/js/stock/stock_transfer_req.js')}}"></script>
@endpush
