<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="row" style="width: 100%; height: 50px; background-color: blue; color: white; margin: 0;">
                <span class="mt-2">
                    <h4 style="color: white;">CREATE AN ACCOUNT</h4>
                </span>
            </div>
            <span>Enter all required fields to create an account.</span>
            <div class="modal-body" style="background-color: #F0F0F0;">
                <form id="registerCustomerForm" action="{{route('customer.store')}}">
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <label for="name" class="form-label">Customer Name <font style="color: red;">*</font>
                            </label>
                            <input type="text" name="name" class="form-control form-control-sm rounded-0" id="name"
                                placeholder="Customer Name">
                        </div>
                        <div class="col-md-12 mb-1">
                            <label for="input4" class="form-label">Mobile No. <font style="color: red;">*</font></label>
                            <input type="text" name="mobile" class="form-control form-control-sm rounded-0" id="mobile"
                                placeholder="Mobile">
                        </div>

                        <div class="col-md-12 mb-1">
                            <label for="input4" class="form-label">VAT No. <font style="color: red;">*</font></label>
                            <input type="text" name="vat" class="form-control form-control-sm rounded-0" id="mobile"
                                placeholder="VAT #">
                            <input type="hidden" name="longitude" class="form-control form_control" id="longitude"
                                value="{{$userLocation->longitude}}">
                            <input type="hidden" name="latitude" class="form-control form_control" id="latitude"
                                value="{{$userLocation->latitude}}">
                        </div>

                        <!-- <div class="col-md-12 mb-1">
                            <label for="cnic" class="form-label">CNIC</label>
                            <input type="text" name="cnic" class="form-control form-control-sm rounded-0" id="cnic"
                                placeholder="CNIC">
                        </div> -->

                        <!-- <div class="col-md-12 mb-1">
                            <label for="address" class="form-label">Address</label>
                            <textarea rows="3" cols="5" class="form-control" name="address" id="AddressEn"></textarea>
                        </div> -->

                        <div id="repeaterContainer">
                            <!-- Initial row with input and buttons -->
                            <div class="col-md-2">
                                <label for="address" class="col-form-label">Address</label>
                            </div>
                            <div class="form-row repeater-row mb-3">
                                <div class="col-md-12">
                                    <input type="text" class="form-control AddressEn" name="address[]"
                                        placeholder="Address">
                                </div>
                                <div class="col-md-2 mt-1">
                                    <button type="button" class="btn btn-success btn-sm repeater-add">+</button>
                                    <button type="button" class="btn btn-danger btn-sm repeater-remove">-</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div id="map" style="width: 100%; height:500px"></div>
                        <p><input class="postcode" id="Postcode" name="Postcode" type="text" value="New York, NY">
                            <input type="button" id="findbutton" value="Find" /></p>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-warning rounded-0"
                                data-bs-dismiss="modal">Close</button>
                        </div>

                        <div class="col-md-9 text-end">

                            <button type="submit" class="btn btn-block rounded-0"
                                style="width:80% !important; margin: auto; background-color: black; color: white;">
                                <span id="spinner" class="" role="status" aria-hidden="true"></span>&nbsp;&nbsp;
                                <span class="saveCustomerBtn">Create Customer</span>
                            </button>
                        </div>

                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
