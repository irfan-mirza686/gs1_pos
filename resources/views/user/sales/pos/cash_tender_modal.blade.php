<div class="modal fade" id="cashTenderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F0F0F0; height: 40px;">
                <h5 class="modal-title">Tender Amount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="row" style="width: 100%; height: 50px; background-color: #4F2259; color: #F7F676; margin: 0;">
                <span class="mt-2"><h4>Cash Sale Amount</h4></span>
            </div>
            <div class="modal-body" style="background-color: #F0F0F0;">

                <div class="row mb-1">
                    <label for="totalAmount" class="col-sm-4 col-form-label"><strong>Total Amount</strong></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control rounded-0 text-end" value="0.00" id="totalAmount" name="totalAmount" placeholder="Choose Password">
                    </div>
                </div>
                <div class="row mb-1">
                    <label for="cashAmount" class="col-sm-4 col-form-label"><strong>Cash Amount</strong></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control rounded-0 text-end" id="cashAmount" value="0.00" name="cashAmount" placeholder="Choose Password">
                    </div>
                </div>
                <div class="row mb-1">
                    <label for="spanAmount" class="col-sm-4 col-form-label"><strong>Span Amount</strong></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control rounded-0 text-end" value="0.00" id="spanAmount" placeholder="Choose Password">
                    </div>
                </div>
                <div class="row mb-1">
                    <label for="tenderAmount" class="col-sm-4 col-form-label"><strong>Tender Amount</strong></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control rounded-0 text-end" value="0.00" id="tenderAmount" name="tender_amount" placeholder="Choose Password">
                    </div>
                </div>
                <div class="row mb-1">
                    <label for="input38" class="col-sm-4 col-form-label"><strong>Change</strong></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control rounded-0 text-end" value="0.00" id="showChange" name="change_amount" placeholder="Choose Password">
                    </div>
                </div>
                <div class="row mt-3">
                    <button type="submit" id="invoiceSubmitBtn" class="btn btn-block rounded-0 disabled" style="width:80% !important; margin: auto; background-color: #4F2259; color: white;"><span id="submitInvoiceSpinner" class="" role="status" aria-hidden="true"></span>&nbsp;&nbsp;Submit Invoice</button>
                    <button type="button" class="btn btn-block rounded-0 mt-2" data-bs-dismiss="modal" style="background-color: red; color: white; width: 100px;">Cancel</button>
                </div>
            </div>
          
        </div>
    </div>
</div>