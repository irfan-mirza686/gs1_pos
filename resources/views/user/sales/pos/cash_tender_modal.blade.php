<style>
        .modal-dialog {
            max-width: 900px;
        }
        .modal-content {
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            border-radius: 15px;
            padding: 20px;
        }
        .item-list, .payment-details, .payment-method {
            padding: 20px;
        }
        .item-list {
            width: 35%;
            border-right: 1px solid #e5e5e5;
        }
        .payment-details {
            width: 35%;
        }
        .payment-method {
            width: 30%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .number-pad button {
            width: 100%;
            margin-bottom: 5px;
        }
        .payment-method button {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
        }
        .payment-method img {
            margin-right: 10px;
        }
        .back-button {
            font-size: 24px;
            padding: 6px 0;
        }
        .payment-method span {
            font-size: 12px;
        }
        .display-itmes {
            max-height: 250px; /* Adjust this height as needed */
            overflow-y: auto;
        }
    </style>
   <div class="container mt-5">

        <!-- Modal -->
        <div class="modal fade" id="cashTenderModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div> -->
                    <div class="modal-body d-flex">
                        <div class="item-list">
                            <div class="display-itmes">
                                <!-- <div class="d-flex justify-content-between">
                                    <div>1 x Burger of the Day</div>
                                    <div>$16.49</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>1 x Burger of the Day</div>
                                    <div>$16.49</div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <div>Subtotal</div>
                                    <div>$42.94</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>Tax (GST, 5%)</div>
                                    <div>$2.15</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div>Tax (PST, 7%)</div>
                                    <div>$3.01</div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <h5>Total</h5>
                                    <h5>$48.10</h5>
                                </div> -->
                                </div>
                                <div class="display-total mb-2">

                                </div>
                                <!-- <button type="submit" class="btn btn-primary w-100 mt-3 shadow"  id="submitInvoiceSpinnerx">Print</button> -->
                                <button type="submit" id="invoiceSubmitBtn" class="btn btn-primary" style="width: 80%; margin: auto;">
                            <span id="submitInvoiceSpinner" class="" role="status" aria-hidden="true"></span>&nbsp;&nbsp; Print
                        </button>
                        </div>
                        <div class="payment-details">
                            <div>
                                <h5>Amount due</h5>
                                <input type="text" class="form-control mb-2" id="amountDue" name="totalAmount" value="" readonly>
                                <h5>Amount received</h5>
                                <input type="text" class="form-control mb-2" id="amountReceived" name="tender_amount" placeholder="SAR 0.00" readonly>
                                <h5>Change</h5>
                                <input type="text" class="form-control mb-2" id="changeAmount" name="change_amount" placeholder="SAR 0.00" readonly>
                                <div class="row number-pad mt-3">
                                    <div class="col-4"><button class="btn btn-secondary shadow">1</button></div>
                                    <div class="col-4"><button class="btn btn-secondary shadow">2</button></div>
                                    <div class="col-4"><button class="btn btn-secondary shadow">3</button></div>
                                    <div class="col-4"><button class="btn btn-secondary shadow">4</button></div>
                                    <div class="col-4"><button class="btn btn-secondary shadow">5</button></div>
                                    <div class="col-4"><button class="btn btn-secondary shadow">6</button></div>
                                    <div class="col-4"><button class="btn btn-secondary shadow">7</button></div>
                                    <div class="col-4"><button class="btn btn-secondary shadow">8</button></div>
                                    <div class="col-4"><button class="btn btn-secondary shadow">9</button></div>
                                    <div class="col-4"><button class="btn btn-secondary shadow">0</button></div>
                                    <div class="col-4"><button class="btn btn-danger shadow" id="backButton">Back</button></div>
                                    <!-- <div class="col-4"><button class="btn btn-secondary">$48.10</button></div>
                                    <div class="col-4"><button class="btn btn-secondary">$50.00</button></div>
                                    <div class="col-4"><button class="btn btn-secondary">$60.00</button></div> -->
                                </div>
                                <!-- <div class="d-flex justify-content-between mt-3">
                                    <button class="btn btn-secondary">Discount</button>
                                    <button class="btn btn-secondary">Split Evenly</button>
                                </div> -->
                            </div>
                        </div>
                        <div class="payment-method">
                        <button class="btn shadow"><img src="{{asset('assets/uploads/cash_tender/cash.png')}}" width="50" height="50"  alt="Cash"> Cash</button>
                            <button class="btn shadow"><img src="{{asset('assets/uploads/cash_tender/credit_debit.png')}}" width="50" height="50"  alt="Credit/Debit"><span>Credit/Debit</span></button>
                            <button class="btn shadow"><img src="{{asset('assets/uploads/cash_tender/vm.png')}}" width="50" height="50"  alt="Visa/Master"><span>Visa/Master</span></button>
                            <button class="btn shadow"><img src="{{asset('assets/uploads/cash_tender/usex.png')}}" width="50" height="50"  alt="American Express"><span>American Express</span></button>
                            <button class="btn shadow"><img src="{{asset('assets/uploads/cash_tender/paypal.png')}}" width="50" height="50"  alt="PayPal"><span>PayPal</span></button>
                            <button class="btn shadow"><img src="{{asset('assets/uploads/cash_tender/wallet.png')}}" width="50" height="50"  alt="Wallet"><span>Wallet</span></button>
                            <button class="btn shadow"><img src="{{asset('assets/uploads/cash_tender/bitcoin.png')}}" width="50" height="50"  alt="Bitcoin"><span>Bitcoin</span></button>
                            <button class="btn shadow"><img src="{{asset('assets/uploads/cash_tender/stc.png')}}" width="50" height="50"  alt="STC Pay"><span>STC Pay</span></button>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

