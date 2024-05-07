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
    <div class="row mt-1">
        <div class="col-md-3">
            <label for="transactions" class="form-label">Transaction <font style="color: red;">*
                </font>
            </label>
            <select class="single-select appendHscodes form-control rounded-0" name="transactions" id="transactions">
                <option disabled selected>Choose...</option>

            </select>
        </div>

        <div class="col-md-3">
            <label for="salesLocation" class="form-label">Sales Location <font style="color: red;">*
                </font>
            </label>
            <select class="single-select appendHscodes form-control rounded-0" name="salesLocation" id="salesLocation">
                <option disabled selected>Choose...</option>

            </select>
        </div>

        <div class="mb-3 col-md-3">
            <label for="MemberID" class="form-label">VAT # </label>
            <input type="text" class="form-control rounded-0" id="vat_no" name="vat_no" placeholder="Vat Number"
                value="">

        </div>

        <div class="mb-3 col-md-3">
            <label for="invoice_no" class="form-label">Invoice # <font style="color: red;">*</font></label>
            <input type="text" class="form-control rounded-0" id="invoice_no" name="invoice_no" placeholder="Invoice #"
                value="{{$printInvoiceNo}}" readonly style="background-color: #D8FDBA">

        </div>

    </div>
    <div class="row mt-1">
        <div class="mb-3 col-md-3">
            <label for="searchCustomer" class="form-label">Search Customer <font style="color: red;">*</font>
            </label>
            <input type="text" class="form-control rounded-0" id="searchCustomer" name="mobileNumber"
                placeholder="Search Customer by Mobile & Name ..." value="">

        </div>

        <div class="col-md-3">
            <label for="delivery" class="form-label">Delivery <font style="color: red;">*
                </font>
            </label>
            <select class="single-select form-control rounded-0" name="delivery" id="delivery">
                <option disabled selected>Choose...</option>

            </select>
        </div>

        <div class="mb-3 col-md-3">
            <label for="customerName" class="form-label">Customer Name <font style="color: red;">*</font>
            </label>
            <input type="text" class="form-control rounded-0" id="customerName" name="customerName"
                placeholder="Customer Name" value="" readonly style="background-color: #D8FDBA">
            <input type="hidden" name="customer_id" value="" id="customerID">
            <!-- <span>Current Balance: <span style="color: red;">2500</span></span> -->
        </div>
        <div class="mb-3 col-md-3">
            <label for="MemberID" class="form-label">Mobile # </label>
            <input type="text" class="form-control rounded-0" id="mobile" name="mobile" placeholder="Mobile Number"
                value="">

        </div>

    </div>
    <div class="row mt-1">


        <div class="mb-3 col-md-6">
            <label for="MemberID" class="form-label">Remarks </label>
            <input type="text" class="form-control rounded-0" id="remkars" name="remkars" placeholder="Remarks"
                value="">

        </div>

        <div class="col-md-3">
            <label for="type" class="form-label">Type <font style="color: red;">*
                </font>
            </label>
            <select class="single-select form-control rounded-0" name="type" id="type">
                <option disabled selected>Choose...</option>

            </select>
        </div>

    </div>

    <div class="row">
        <label for="" class="form-label"><b>Scan Barode</b>
            <font style="color: red;">*</font>
            <div class="input-group mb-3">
                <input type="text" class="form-control rounded-0" id="barcode" name="barcode"
                    placeholder="Scan Barcode..." onmouseover="this.focus();"> <span class="input-group-text rounded-0"
                    id="basic-addon2"><i class="fadeIn animated bx bx-barcode-reader"></i></span>
                <div class="barcodeLoader d-none" style="margin-top: -37px; float: right;">
                    <img src="{{asset('assets/uploads/search-barcode.gif')}}" width="40" height="35"
                        style="margin-top: 38px; margin-left: -40px;">
                </div>
            </div>

            <!-- <div class="mb-3 col-md-12">
                <label for="" class="form-label"><b>Scan Barode</b>
                    <font style="color: red;">*</font>
                </label>
                <input type="text" class="form-control rounded-0" id="barcode" name="barcode"
                    placeholder="Scan Barcode..." value="">
                <div class="barcodeLoader" style="margin-top: -37px; float: right; display: none;">
                    <img src="{{asset('assets/images/search-barcode.gif')}}" width="38" height="36">
                </div>
            </div> -->

    </div>

</div>
