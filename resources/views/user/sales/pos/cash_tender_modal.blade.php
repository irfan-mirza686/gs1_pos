
    <style>
        .modal-header-custom {
            background-color: #4F2259;
            color: white;
        }
        .modal-body-custom {
            background-color: #F0F0F0;
        }
        .payment-methods img {
            width: 50px;
            margin: 0 10px;
            transition: transform 0.2s ease-in-out;
        }
        .payment-methods img:hover {
            transform: scale(1.1);
        }
        .btn-submit-custom {
            background-color: #4F2259;
            color: white;
        }
        .btn-submit-custom:hover {
            background-color: #6D397C;
        }
        .form-control-custom {
            border-radius: 0;
            text-align: right;
        }
    </style>

    <div class="modal fade" id="cashTenderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title" style="color: white;">Tender Amount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <div class="form-group row mb-3">
                        <label for="totalAmount" class="col-sm-4 col-form-label"><strong>Total Amount</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-custom" value="0.00" id="totalAmount" name="totalAmount" placeholder="Total Amount">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="cashAmount" class="col-sm-4 col-form-label"><strong>Cash Amount</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-custom" id="cashAmount" value="0.00" name="cashAmount" placeholder="Cash Amount">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="spanAmount" class="col-sm-4 col-form-label"><strong>Span Amount</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-custom" value="0.00" id="spanAmount" placeholder="Span Amount">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="tenderAmount" class="col-sm-4 col-form-label"><strong>Tender Amount</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-custom" value="0.00" id="tenderAmount" name="tender_amount" placeholder="Tender Amount">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="showChange" class="col-sm-4 col-form-label"><strong>Change</strong></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-custom" value="0.00" id="showChange" name="change_amount" placeholder="Change Amount">
                        </div>
                    </div>
                    <!-- Online Payment Methods Section -->
                    <div class="form-group row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Payment Methods</strong></label>
                        <div class="col-sm-8 payment-methods d-flex align-items-center">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png" alt="MasterCard">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/3/39/PayPal_logo.svg" alt="PayPal">
                        </div>
                    </div>
                    <div class="form-group row mt-4">
                        <button type="submit" id="invoiceSubmitBtn" class="btn btn-block btn-submit-custom" style="width: 80%; margin: auto;">
                            <span id="submitInvoiceSpinner" class="" role="status" aria-hidden="true"></span>&nbsp;&nbsp;Submit Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

