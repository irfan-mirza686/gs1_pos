@extends("user.layouts.layout")
@section('title', '| Add Product')

@section("style")
<link href="{{asset('assets/plugins/Drag-And-Drop/dist/imageuploadify.min.css')}}" rel="stylesheet" />
<link href="{{asset('assets/plugins/datetimepicker/css/classic.css')}}" rel="stylesheet" />
<link href="{{asset('assets/plugins/datetimepicker/css/classic.time.css')}}" rel="stylesheet" />
<link href="{{asset('assets/plugins/datetimepicker/css/classic.date.css')}}" rel="stylesheet" />
<link rel="stylesheet"
    href="{{asset('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css')}}">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<style>
    .image-box {
        position: relative;
        width: 200px;
        height: 200px;
        border: 2px dashed #ccc;
        display: inline-block;
        margin-right: 20px;
    }

    .image-box input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .image-label {
        display: block;
        width: 100%;
        height: 100%;
        text-align: center;
        cursor: pointer;
        position: relative;
    }

    .image-label img {
        max-width: 100%;
        max-height: 100%;
        display: none;
    }

    .image-placeholder-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #777;
        font-size: 14px;
    }
</style>
@endsection

@section("content")
<div class="page-wrapper">
    <div class="page-content">
        @include('user.show_flash_msgs')
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Product</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-header">
                <div class="ms-auto">
                    <div class="col">

                        <a href="{{route('products')}}" class="btn btn-primary px-5 rounded-0"
                            style="float: right;">Back</a>
                    </div>
                </div>
            </div>
            <form action="{{route('product.store')}}" method="post" enctype="multipart/form-data">@csrf
                <div class="card-body p-4">
                    <h5 class="card-title">Add New Product</h5>
                    <hr />
                    <div class="form-body mt-4">
                        <div class="row">

                            <div class="col-lg-8">
                                <div class="border border-3 p-4 rounded">
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label for="ProductNameE" class="form-label">Product Type <font
                                                    style="color: red;">*</font></label>
                                            <select class="single-select" name="product_type" id="product_type">
                                                <option disabled selected>Choose...</option>
                                                <option value="gs1">GS1</option>
                                                <option value="non_gs1">Non GS1</option>
                                            </select>

                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="productNameEn" class="form-label">Product Name EN <font
                                                    style="color: red;">*</font></label>
                                            <input type="text" class="form-control" id="productNameEn"
                                                name="productnameenglish" placeholder="Product Name EN"
                                                value="{{old('productnameenglish')}}">

                                        </div>
                                        <div class="mb-3 col-md-6 gs1" style="display:none;">
                                            <label for="productnamearabic" class="form-label">Product Name AR <font
                                                    style="color: red;">*</font></label>
                                            <input type="text" class="form-control" id="productnamearabic"
                                                name="productnamearabic" placeholder="Product Name AR"
                                                value="{{old('productnamearabic')}}">

                                        </div>
                                        <div class="mb-3 col-md-6 gs1" style="display:none;">
                                            <label for="size" class="form-label">Size <font style="color: red;">*</font>
                                            </label>
                                            <input type="text" class="form-control" id="size" name="size"
                                                placeholder="size" value="{{old('size')}}">

                                        </div>
                                        <div class="mb-3 col-md-6 gs1" style="display:none;">
                                            <label for="producturl" class="form-label">Product URL <font
                                                    style="color: red;">*</font></label>
                                            <input type="text" class="form-control" id="producturl" name="product_url"
                                                placeholder="Product URL" value="{{old('BrandName')}}">
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="purchase_price" class="form-label">Purchase Price <font
                                                    style="color: red;">*</font>
                                            </label>
                                            <input type="text" class="form-control" id="purchase_price"
                                                name="purchase_price" placeholder="purchase_price"
                                                value="{{old('purchase_price')}}">

                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="selling_price" class="form-label">Selling Price <font
                                                    style="color: red;">*</font></label>
                                            <input type="text" class="form-control" id="selling_price"
                                                name="selling_price" placeholder="Selling Price"
                                                value="{{old('selling_price')}}">
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="product_code" class="form-label">Product Code <font
                                                    style="color: red;">*</font></label>
                                            <input type="text" class="form-control" id="product_code"
                                                name="product_code" placeholder="Product Code"
                                                value="{{old('product_code')}}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="descriptionen" class="form-label">Description EN</label>
                                            <textarea class="form-control" name="details_page" id="descriptionen"
                                                rows="3"></textarea>
                                        </div>
                                        <div class="mb-3 gs1" style="display:none;">
                                            <label for="details_page_ar" class="form-label">Description AR</label>
                                            <textarea class="form-control" name="details_page_ar" id="details_page_ar"
                                                rows="3"></textarea>
                                        </div>

                                        <div class="form-row p-2 gs1 mb-3 col-md-6" style="display: none;">
                                            <div class="form-group">

                                                <div class="image-box" id="front_image_box">
                                                    <label for="fron_image">Front Image</label>
                                                    <input type="file" class="image-upload" name="front_image"
                                                        id="front_image" accept="image/*">
                                                    <label for="front_image" class="image-label">
                                                        <img src="{{ asset('placeholder.png') }}" alt="Front Image">
                                                        <span class="image-placeholder-text">Click to upload front
                                                            image</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row p-2 gs1 mb-3 col-md-6" style="display: none;">
                                            <div class="form-group   justify-content-end d-flex">
                                                <div class="image-box" id="back_image_box">
                                                    <label for="back_image">Back Image</label>
                                                    <input type="file" class="image-upload" name="back_image"
                                                        id="back_image" accept="image/*">
                                                    <label for="back_image" class="image-label">
                                                        <img src="{{ asset('placeholder.png') }}" alt="Back Image">
                                                        <span class="image-placeholder-text">Click to upload back
                                                            image</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row p-2 gs1 mb-3 col-md-4" style="display: none;">
                                            <div class="form-group">

                                                <div class="image-box" id="option1_image_box">
                                                    <label for="fron_image">Option 1</label>
                                                    <input type="file" class="image-upload" name="image_1"
                                                        id="option1_image" accept="image/*">
                                                    <label for="option1_image" class="image-label">
                                                        <img src="{{ asset('placeholder.png') }}" alt="option 1">
                                                        <span class="image-placeholder-text">Click to upload option
                                                            1</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row p-2 gs1 mb-3 col-md-4" style="display: none;">

                                            <div class="form-group col-md-4">

                                                <div class="image-box" id="option2_image_box">
                                                    <label for="fron_image">Option 2</label>
                                                    <input type="file" class="image-upload" name="image_2"
                                                        id="option2_image" accept="image/*">
                                                    <label for="option2_image" class="image-label">
                                                        <img src="{{ asset('placeholder.png') }}" alt="option 2">
                                                        <span class="image-placeholder-text">Click to upload option
                                                            2</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row p-2 gs1 mb-3 col-md-4" style="display: none;">
                                            <div class="form-group col-md-4">

                                                <div class="image-box" id="option3_image_box">
                                                    <label for="fron_image">Option 3</label>
                                                    <input type="file" class="image-upload" name="image_3"
                                                        id="option3_image" accept="image/*">
                                                    <label for="option3_image" class="image-label">
                                                        <img src="{{ asset('placeholder.png') }}" alt="option 3">
                                                        <span class="image-placeholder-text">Click to upload option
                                                            3</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="border border-3 p-4 rounded">
                                    <div class="row g-3">

                                        <div class="col-12 gs1" style="display: none;">
                                            <label for="BrandName" class="form-label">Brand Name EN <font
                                                    style="color: red;">*</font></label>
                                            <select class="single-select" name="BrandName" id="BrandName">
                                                <option disabled selected>-select-</option>
                                                @foreach($productData['brandsData'] as $brand)
                                                <option value="{{$brand['name_ar']}}">{{$brand['name_ar']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 gs1" style="display: none;">
                                            <label for="BrandNameAr" class="form-label">Brand Name AR </label>
                                            <select class="single-select" name="BrandNameAr" id="BrandNameAr">
                                                <option disabled selected>-select-</option>
                                                @foreach($productData['brandsData'] as $brand)
                                                <option value="{{$brand['name_ar']}}">{{$brand['name_ar']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="unit" class="form-label">Unit Code</label>
                                            <select class="single-select" name="unit" id="unit">
                                                <option disabled selected>-select-</option>
                                                @foreach($productData['unitsData'] as $unit)
                                                <option value="{{$unit['unit_name']}}">{{$unit['unit_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 gs1" style="display: none;">
                                            <label for="Origin" class="form-label">Origin</label>
                                            <select class="single-select" name="Origin" id="Origin">
                                                <option disabled selected>-select-</option>
                                                @foreach($productData['countryOfSaleData'] as $country)
                                                <option value="{{$country['Alpha3']}}">{{$country['country_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 gs1" style="display: none;">
                                            <label for="countrySale" class="form-label">Country Of Sale <font
                                                    style="color: red;">*</font>
                                            </label>
                                            <select class="single-select" name="countrySale" id="countrySale">
                                                <option disabled selected>Choose...</option>
                                                @foreach($productData['countryOfSaleData'] as $country)
                                                <option value="{{$country['Alpha3']}}">{{$country['country_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 gs1" style="display: none;">
                                            <label for="prod_lang" class="form-label">Product Description Language <font
                                                    style="color: red;">*
                                                </font></label>
                                            <select class="single-select" name="prod_lang" id="prod_lang">
                                                <option value="" disabled selected>Choose...</option>
                                                @foreach($productData['prodLangSaleData'] as $lang)
                                                <option value="{{$lang['language_name']}}">{{$lang['language_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 gs1" style="display: none;">
                                            <label for="gtin_type" class="form-label">GTIN Type <font
                                                    style="color: red;">*</font>
                                            </label>
                                            <select class="single-select" name="ProductType" id="gtin_type">
                                                <option disabled selected>Choose...</option>
                                                @foreach($productData['prodTypesData'] as $type)
                                                <option value="{{$type['name']}}">{{$type['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 gs1" style="display: none;">
                                            <label for="PackagingType" class="form-label">Package Type <font
                                                    style="color: red;">*</font>
                                            </label>
                                            <select class="single-select" name="PackagingType" id="PackagingType">
                                                <option disabled selected>Choose...</option>
                                                @foreach($productData['pkgTypesData'] as $pkg_type)
                                                <option value="{{$pkg_type['name']}}">{{$pkg_type['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 gs1" style="display: none;">
                                            <label for="gpc" class="form-label">GPC <font style="color: red;">*</font>
                                            </label>
                                            <select class="single-select appendGpc" name="gpc" id="appendGpc">
                                                <option disabled selected>Choose...</option>

                                            </select>
                                        </div>

                                        <div class="col-12 gs1" style="display: none;">
                                            <label for="hscode" class="form-label">HSCode <font style="color: red;">*
                                                </font>
                                            </label>
                                            <select class="single-select appendHscodes" name="HsDescription"
                                                id="appendHscodes">
                                                <option disabled selected>Choose...</option>

                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Save Product</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--end row-->
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection

@push("custom-script")
<script src="{{asset('assets/admin/js/product/product_add_edit.js')}}"></script>

<script>
    $(".image-upload").change(function() {
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(input).siblings('label').find('img').attr('src', e.target.result);
                $(input).siblings('label').find('.image-placeholder-text').hide();
                $(input).siblings('label').find('img').show();
                $(input).siblings('input[type="hidden"]').val(e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>

@endpush
