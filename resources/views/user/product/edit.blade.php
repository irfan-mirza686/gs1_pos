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

<style>
    .image-container {
        position: relative;
        width: 150px;
        height: 150px;
        border: 1px solid #ddd;
        margin-bottom: 10px;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-container input[type="file"] {
        display: none;
    }

    .image-container label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        text-align: center;
        padding: 5px;
        cursor: pointer;
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
                        <li class="breadcrumb-item active" aria-current="page">Update</li>
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
            <form action="{{route('product.update',$editProduct['id'])}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_type" value="{{$product_type}}">
                @if($product_type=='gs1')
                <input type="hidden" name="product_id" value="{{$editProduct['v2_productID']}}">
                @else
                <input type="hidden" name="product_id" value="{{$editProduct['id']}}">
                @endif
                <div class="card-body p-4">
                    <h5 class="card-title">Update Product</h5>
                    <hr />
                    <div class="form-body mt-4">
                        <div class="row">

                            <div class="col-lg-8">
                                <div class="border border-3 p-4 rounded">
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label for="ProductNameE" class="form-label">Product Type <font
                                                    style="color: red;">*</font></label>
                                            <select class="single-select form-control" name="product_type"
                                                id="product_type" disabled>
                                                <option disabled selected>Choose...</option>
                                                <option value="gs1" {{($product_type=='gs1')?'selected':''}}>GS1
                                                </option>
                                                <option value="non_gs1" {{($product_type=='non_gs1')?'selected':''}}>Non
                                                    GS1</option>
                                            </select>

                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="productNameEn" class="form-label">Product Name EN <font
                                                    style="color: red;">*</font></label>
                                            <input type="text" class="form-control" id="productNameEn"
                                                name="productnameenglish" placeholder="Product Name EN"
                                                value="{{ $editProduct['productnameenglish'] ?? old('productnameenglish')}}">

                                        </div>
                                        @if($product_type=='gs1')
                                        <div class="mb-3 col-md-6">
                                            <label for="productnamearabic" class="form-label">Product Name AR <font
                                                    style="color: red;">*</font></label>
                                            <input type="text" class="form-control" id="productnamearabic"
                                                name="productnamearabic" placeholder="Product Name AR"
                                                value="{{ $editProduct['productnamearabic'] ?? old('productnamearabic')}}">

                                        </div>
                                        @endif
                                        <div class="mb-3 col-md-6">
                                            <label for="size" class="form-label">Size <font style="color: red;">*</font>
                                            </label>
                                            <input type="text" class="form-control" id="size" name="size"
                                                placeholder="size" value="{{ $editProduct['size'] ?? old('size')}}">

                                        </div>
                                        @if($product_type=='gs1')
                                        <div class="mb-3 col-md-6">
                                            <label for="producturl" class="form-label">Product URL <font
                                                    style="color: red;">*</font></label>
                                            <input type="text" class="form-control" id="producturl" name="product_url"
                                                placeholder="Product URL"
                                                value="{{ $editProduct['BrandName'] ?? old('BrandName')}}">
                                        </div>
                                        @endif
                                        <div class="mb-3 col-md-6">
                                            <label for="purchase_price" class="form-label">Purchase Price <font
                                                    style="color: red;">*</font>
                                            </label>
                                            <input type="text" class="form-control" id="purchase_price"
                                                name="purchase_price" placeholder="purchase_price"
                                                value="{{ $editProduct['purchase_price'] ?? old('purchase_price')}}">

                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label for="selling_price" class="form-label">Selling Price <font
                                                    style="color: red;">*</font></label>
                                            <input type="text" class="form-control" id="selling_price"
                                                name="selling_price" placeholder="Selling Price"
                                                value="{{ $editProduct['selling_price'] ?? old('selling_price')}}">
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="quantity" class="form-label">Quantity <font style="color: red;">
                                                    *</font></label>
                                            <input type="text" class="form-control" id="quantity" name="quantity"
                                                placeholder="Product Code"
                                                value="{{ $editProduct['quantity'] ?? old('quantity')}}">
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="barcode" class="form-label">Barcode <font style="color: red;">*
                                                </font></label>
                                            <input type="text" class="form-control" id="barcode" name="product_code"
                                                placeholder="Barcode"
                                                value="{{ $editProduct['barcode'] ?? old('barcode')}}" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label for="descriptionen" class="form-label">Description EN</label>
                                            <textarea class="form-control" name="details_page" id="descriptionen"
                                                rows="3">{!! $editProduct['details_page'] !!}</textarea>
                                        </div>
                                        @if($product_type=='gs1')
                                        <div class="mb-3">
                                            <label for="details_page_ar" class="form-label">Description AR</label>
                                            <textarea class="form-control" name="details_page_ar" id="details_page_ar"
                                                rows="3">{!! $editProduct['details_page_ar'] !!}</textarea>
                                        </div>
                                        @endif
                                        <?php

                                            $front_image = $editProduct['front_image'] ? getFile('products',$editProduct['front_image']): asset('assets/uploads/no-image.png');
                                            $back_image = $editProduct['back_image'] ? getFile('products',$editProduct['back_image']): asset('assets/uploads/no-image.png');
                                            $image_1 = $editProduct['image_1'] ? getFile('products',$editProduct['image_1']): asset('assets/uploads/no-image.png');
                                            $image_2 = $editProduct['image_2'] ? getFile('products',$editProduct['image_2']): asset('assets/uploads/no-image.png');
                                            $image_3 = $editProduct['image_3'] ? getFile('products',$editProduct['image_3']): asset('assets/uploads/no-image.png');

                                            // $back_image = $url . $editProduct['back_image'];


                                        ?>
                                        <div class="form-row p-2 gs1 mb-3 col-md-6">
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <div class="image-container">
                                                        <img src="{{$front_image}}" id="frontImagePreview"
                                                            alt="Front Image">
                                                        <input type="file" id="frontImage" name="front_image"
                                                            onchange="previewImage(event, 'frontImagePreview')">
                                                        <label for="frontImage">Front Image</label>
                                                    </div>
                                                </div>
                                                <!-- <div class="image-box" id="front_image_box">
                                                    <label for="fron_image">Front Image</label>
                                                    <input type="file" class="image-upload" name="front_image"
                                                        id="front_image" accept="image/*">
                                                    <label for="front_image" class="image-label">
                                                    <img id="front_image_preview" src="{{ $front_image }}" alt="Front Image">
                                                        <span class="image-placeholder-text">Click to upload front
                                                            image</span>
                                                    </label>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="form-row p-2 gs1 mb-3 col-md-6">
                                            <div class="form-group  d-flex">
                                                <div class="col-md-2">
                                                    <div class="image-container">
                                                        <img src="{{$back_image}}" id="backImagePreview"
                                                            alt="Back Image">
                                                        <input type="file" id="backImage" name="back_image"
                                                            onchange="previewImage(event, 'backImagePreview')">
                                                        <label for="backImage">Back Image</label>
                                                    </div>
                                                </div>
                                                <!-- <div class="image-box" id="back_image_box">
                                                    <label for="back_image">Back Image</label>
                                                    <input type="file" class="image-upload" name="back_image"
                                                        id="back_image" accept="image/*">
                                                    <label for="back_image" class="image-label">
                                                        <img src="{{ $front_image }}" alt="Back Image">
                                                        <span class="image-placeholder-text">Click to upload back
                                                            image</span>
                                                    </label>
                                                </div> -->
                                            </div>
                                        </div>
                                        @if($product_type=='gs1')
                                        <div class="form-row p-2 gs1 mb-3 col-md-4">
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <div class="image-container">
                                                        <img src="{{ $image_1 }}" id="option1Preview" alt="Option 1">
                                                        <input type="file" id="option1" name="image_1"
                                                            onchange="previewImage(event, 'option1Preview')">
                                                        <label for="option1">Option 1</label>
                                                    </div>
                                                </div>
                                                <!-- <div class="image-box" id="option1_image_box">
                                                    <label for="fron_image">Option 1</label>
                                                    <input type="file" class="image-upload" name="image_1"
                                                        id="option1_image" accept="image/*">
                                                    <label for="option1_image" class="image-label">
                                                        <img src="{{ asset('placeholder.png') }}" alt="option 1">
                                                        <span class="image-placeholder-text">Click to upload option
                                                            1</span>
                                                    </label>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="form-row p-2 gs1 mb-3 col-md-4">

                                            <div class="form-group col-md-4">
                                                <div class="col-md-2">
                                                    <div class="image-container">
                                                        <img src="{{ $image_2 }}" id="option2Preview" alt="Option 2">
                                                        <input type="file" id="option2" name="image_2"
                                                            onchange="previewImage(event, 'option2Preview')">
                                                        <label for="option2">Option 2</label>
                                                    </div>
                                                </div>
                                                <!-- <div class="image-box" id="option2_image_box">
                                                    <label for="fron_image">Option 2</label>
                                                    <input type="file" class="image-upload" name="image_2"
                                                        id="option2_image" accept="image/*">
                                                    <label for="option2_image" class="image-label">
                                                        <img src="{{ asset('placeholder.png') }}" alt="option 2">
                                                        <span class="image-placeholder-text">Click to upload option
                                                            2</span>
                                                    </label>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="form-row p-2 gs1 mb-3 col-md-4">
                                            <div class="form-group col-md-4">
                                                <div class="col-md-2">
                                                    <div class="image-container">
                                                        <img src="{{ $image_3 }}" id="option3Preview" alt="Option 3">
                                                        <input type="file" id="option3" name="image_3"
                                                            onchange="previewImage(event, 'option3Preview')">
                                                        <label for="option3">Option 3</label>
                                                    </div>
                                                </div>
                                                <!-- <div class="image-box" id="option3_image_box">
                                                    <label for="fron_image">Option 3</label>
                                                    <input type="file" class="image-upload" name="image_3"
                                                        id="option3_image" accept="image/*">
                                                    <label for="option3_image" class="image-label">
                                                        <img src="{{ asset('assets/uploads/no-image.png') }}" alt="option 3">
                                                        <span class="image-placeholder-text">Click to upload option
                                                            3</span>
                                                    </label>
                                                </div> -->
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="border border-3 p-4 rounded">
                                    <div class="row g-3">
                                        @if($product_type=='gs1')
                                        <div class="col-12">
                                            <label for="BrandName" class="form-label">Brand Name EN <font
                                                    style="color: red;">*</font></label>
                                            <select class="single-select form-control" name="BrandName" id="BrandName">
                                                <option disabled selected>-select-</option>
                                                @foreach($productData['brandsData'] as $brand)
                                                <option value="{{$brand['name']}}"
                                                    {{(trim($editProduct['BrandName'])==trim($brand['name']))?'selected':''}}>
                                                    {{$brand['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="BrandNameAr" class="form-label">Brand Name AR </label>
                                            <select class="single-select form-control" name="BrandNameAr"
                                                id="BrandNameAr">
                                                <option disabled selected>-select-</option>
                                                @foreach($productData['brandsData'] as $brand)
                                                <option value="{{$brand['name_ar']}}"
                                                    {{(trim($editProduct['BrandNameAr'])==trim($brand['name_ar']))?'selected':''}}>
                                                    {{$brand['name_ar']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label for="unit" class="form-label">Unit Code</label>
                                            <select class="single-select form-control" name="unit" id="unit">
                                                <option disabled selected>-select-</option>
                                                @foreach($productData['unitsData'] as $unit)
                                                <option value="{{$unit['unit_name']}}"
                                                    {{($editProduct['unit']==$unit['unit_name'])?'selected':''}}>
                                                    {{$unit['unit_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="Origin" class="form-label">Origin</label>
                                            <select class="single-select form-control" name="Origin" id="Origin">
                                                <option disabled selected>-select-</option>
                                                @foreach($productData['countryOfSaleData'] as $country)
                                                <option value="{{$country['country_name']}}"
                                                    {{($editProduct['Origin']==$country['Alpha3'])?'selected':''}}>
                                                    {{$country['country_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="countrySale" class="form-label">Country Of Sale <font
                                                    style="color: red;">*</font>
                                            </label>
                                            <select class="single-select form-control" name="countrySale"
                                                id="countrySale">
                                                <option disabled selected>Choose...</option>
                                                @foreach($productData['countryOfSaleData'] as $country)
                                                <option value="{{$country['Alpha3']}}"
                                                    {{($editProduct['countrySale']==$country['Alpha3'])?'selected':''}}>
                                                    {{$country['country_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="prod_lang" class="form-label">Product Description Language <font
                                                    style="color: red;">*
                                                </font></label>
                                            <select class="single-select form-control" name="prod_lang" id="prod_lang">
                                                <option value="" disabled selected>Choose...</option>
                                                @foreach($productData['prodLangSaleData'] as $lang)
                                                <option value="{{$lang['language_name']}}"
                                                    {{($editProduct['prod_lang']==$lang['language_name'])?'selected':''}}>
                                                    {{$lang['language_name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label for="gtin_type" class="form-label">GTIN Type <font
                                                    style="color: red;">*</font>
                                            </label>
                                            <select class="single-select form-control" name="ProductType"
                                                id="gtin_type">
                                                <option disabled selected>Choose...</option>
                                                @foreach($productData['prodTypesData'] as $type)
                                                <option value="{{$type['name']}}"
                                                    {{($editProduct['ProductType']==$type['name'])?'selected':''}}>
                                                    {{$type['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label for="PackagingType" class="form-label">Package Type <font
                                                    style="color: red;">*</font>
                                            </label>
                                            <select class="single-select form-control" name="PackagingType"
                                                id="PackagingType">
                                                <option disabled selected>Choose...</option>
                                                @foreach($productData['pkgTypesData'] as $pkg_type)
                                                <option value="{{$pkg_type['name']}}"
                                                    {{($editProduct['PackagingType']==$pkg_type['name'])?'selected':''}}>
                                                    {{$pkg_type['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label for="gpc" class="form-label">GPC <font style="color: red;">*</font>
                                            </label>
                                            <select class="single-select form-control appendGpc" name="gpc"
                                                id="appendGpc">
                                                <option disabled selected>Choose...</option>
                                                <option value="{{$editProduct['gpc']}}" selected>{{$editProduct['gpc']}}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label for="hscode" class="form-label">HSCode <font style="color: red;">*
                                                </font>
                                            </label>
                                            <select class="single-select form-control appendHscodes"
                                                name="HsDescription" id="appendHscodes">
                                                <option disabled selected>Choose...</option>
                                                <option value="{{$editProduct['HsDescription']}}" selected>
                                                    {{$editProduct['HsDescription']}}</option>
                                            </select>
                                        </div>
                                        @elseif($product_type=='non_gs1')
                                        <div class="col-12">
                                            <label for="BrandName" class="form-label">Brand Name EN <font
                                                    style="color: red;">*</font></label>
                                            <select class="single-select form-control" name="BrandName" id="BrandName">
                                                <option disabled selected>-select-</option>
                                                @foreach($productData['brandsData'] as $brand)
                                                <option value="{{$brand['name']}}"
                                                    {{($editProduct['BrandName']==$brand['name'])?'selected':''}}>
                                                    {{$brand['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label for="unit" class="form-label">Unit Code</label>
                                            <select class="single-select form-control" name="unit" id="unit">
                                                <option disabled selected>-select-</option>
                                                @foreach($productData['unitsData'] as $unit)
                                                <option value="{{$unit['name']}}"
                                                    {{($editProduct['unit']==$unit['name'])?'selected':''}}>
                                                    {{$unit['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @endif

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
                $('#front_image_preview').attr('src', e.target.result);
                $(input).siblings('label').find('img').attr('src', e.target.result);
                $(input).siblings('label').find('.image-placeholder-text').hide();
                $(input).siblings('label').find('img').show();
                $(input).siblings('input[type="hidden"]').val(e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>

<script>
    function previewImage(event, previewId) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById(previewId);
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endpush
