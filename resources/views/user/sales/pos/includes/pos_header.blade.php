<style>
    /* Custom CSS for responsive form layout */
    .form-group {
        margin-bottom: 20px;
    }

    /* Responsive layout for inputs */
    @media (min-width: 576px) {
        .form-inline-label .form-group {
            display: inline-block;
            width: 50%;
        }
    }

    @media (max-width: 575.98px) {
        .form-inline-label .form-group {
            display: block;
            width: 100%;
        }
    }
    .col-form-label{
        font-size: 11px;
    }
</style>

<div class="card-body" style="background-color: #F0F0F0;">

    <!-- <input type="button" value="Open a Popup Window" onclick="window.open('https://www.quackit.com/javascript/examples/sample_popup.cfm','popUpWindow','height=500,width=400,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');"> -->

    <div class="row">
        <div class="col-md-4">

            <h5><mark>NEW SALE</mark></h5>
        </div>
        <div class="col-md-4 text-center">
            <!-- <h4><mark>Rizwan Mobiles & Repairing</mark></h4> -->
        </div>
        <div class="col-md-4 text-end">
            <h4><mark>Cashier : {{ $user_info['memberData']['company_name_eng'] }}</mark></h4>
        </div>
    </div>
    <!----- First Row----------------->
    <div class="form-group row">
        <label for="transactions" class="col-sm-1 col-form-label">Transactions <font style="color: red;">*</font></label>
        <div class="col-sm-2">
            <select class="single-select appendHscodes form-control rounded-0" name="transactions" id="transactions"
                style="border-radius: 0">
                <option selected>Choose...</option>
                <option value="direct_sales_invoice" selected>Direct Sales Invoice</option>
                <option value="direct_sales_return">Direct Sales Return</option>
            </select>
        </div>
        <label for="salesLocation" class="col-sm-1 col-form-label">Sales Locations <font style="color: red;">*</font></label>
        <div class="col-sm-2">
            <select class="single-select appendHscodes form-control rounded-0" name="salesLocation" id="salesLocation">
                <option disabled selected>Choose...</option>
                @foreach($glnName as $gln)
                <option value="{{$gln}}">{{$gln}}</option>
                @endforeach
            </select>
        </div>
        <label for="vat" class="col-sm-1 col-form-label">VAT # <font style="color: red;">*</font></label>
        <div class="col-sm-2">
            <input type="text" class="form-control" name="vat_no" id="vat_no" placeholder="VAT #">
        </div>
        <label for="invoice_no" class="col-sm-1 col-form-label">Invoice # <font style="color: red;">*</font></label>
        <div class="col-sm-2">
            <input type="text" id="invoice_no" name="order_no" value="{{$printInvoiceNo}}" class="form-control rounded-0"
                aria-describedby="invoice_no" readonly style="background-color: #F0F0F0">
        </div>
    </div>

    <!------- Second Row ---------------->
    <div class="form-group row" style="margin-top: -18px;">
        <label for="searchCustomer" class="col-sm-1 col-form-label">Search Customer <font style="color: red;">*</font></label>
        <div class="col-sm-2">
        <input type="text" class="form-control rounded-0" id="searchCustomer" name="mobileNumber"
                placeholder="Search Customer by Mobile & Name ..." value="" style="background-color: #FFF372">
        </div>
        <label for="delivery" class="col-sm-1 col-form-label">Delivery <font style="color: red;">*</font></label>
        <div class="col-sm-2">
        <select class="single-select form-control rounded-0 delivery" name="delivery" id="delivery">
                <option selected>Choose...</option>

            </select>
        </div>
        <label for="customerName" class="col-sm-1 col-form-label">Customer Name <font style="color: red;">*</font></label>
        <div class="col-sm-2">
        <input type="text" class="form-control rounded-0" id="customerName" name="customerName"
                placeholder="Customer Name" value="" readonly style="background-color: #D8FDBA">
            <input type="hidden" name="customer_id" value="" id="customerID">
        </div>
        <label for="mobile" class="col-sm-1 col-form-label">Mobile # <font style="color: red;">*</font></label>
        <div class="col-sm-2">
        <input type="text" class="form-control rounded-0" id="mobile" name="mobile" placeholder="Mobile Number"
                value="">
        </div>
    </div>



    <!---- Third Row ------->
    <div class="form-group row" style="margin-top: -18px;">
    <label for="remkars" class="col-sm-1 col-form-label">Scan Barode <font style="color: red;">*</font></label>
        <div class="col-sm-2">
        <div class="input-group mb-3">
                <input type="text" class="form-control rounded-0" id="barcode" name="barcode"
                    placeholder="Scan Barcode..." onmouseover="this.focus();" style="background-color: #FFF372;"> <span class="input-group-text rounded-0"
                    id="basic-addon2"><i class="fadeIn animated bx bx-barcode-reader"></i></span>
                <div class="barcodeLoader d-none" style="margin-top: -37px; float: right;">
                    <img src="{{asset('assets/uploads/search-barcode.gif')}}" width="40" height="35"
                        style="margin-top: 38px; margin-left: -40px;">
                </div>
            </div>
        </div>

        <label for="remkars" class="col-sm-1 col-form-label">Remarks <font style="color: red;">*</font></label>
        <div class="col-sm-5">
        <input type="text" class="form-control rounded-0" id="remkars" name="remkars" placeholder="Remarks"
                value="">
        </div>
        <label for="Type" class="col-sm-1 col-form-label">Type <font style="color: red;">*</font></label>
        <div class="col-sm-2">
        <select class="single-select form-control rounded-0" name="type" id="type">
                <option selected>Choose...</option>
                <option value="cash">Cash</option>
                <option value="credit">Credit</option>

            </select>
        </div>

    </div>

    <!--- Fourth row --------->
    <!-- <div class="form-group row">
        <label for="remkars" class="col-sm-1 col-form-label">Scan Barode <font style="color: red;">*</font></label>
        <div class="col-sm-5">
        <div class="input-group mb-3">
                <input type="text" class="form-control rounded-0" id="barcode" name="barcode"
                    placeholder="Scan Barcode..." onmouseover="this.focus();" style="background-color: #FFF372;"> <span class="input-group-text rounded-0"
                    id="basic-addon2"><i class="fadeIn animated bx bx-barcode-reader"></i></span>
                <div class="barcodeLoader d-none" style="margin-top: -37px; float: right;">
                    <img src="{{asset('assets/uploads/search-barcode.gif')}}" width="40" height="35"
                        style="margin-top: 38px; margin-left: -40px;">
                </div>
            </div>
        </div>


    </div> -->




</div>
