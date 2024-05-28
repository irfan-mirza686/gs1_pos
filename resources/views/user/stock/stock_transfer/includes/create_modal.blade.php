<!-- stock_transfer_modal.blade.php -->

<div class="modal fade" id="stockTransferModal" tabindex="-1" aria-labelledby="stockTransferModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockTransferModalLabel">Stock Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="stockTransferForm" action="">

                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-md-6 position-relative">
                            <label for="productSearch" class="form-label">Product</label>
                            <input type="text" class="form-control" id="productSearch"
                                placeholder="Search for a product">
                            <div id="searchResults" class="list-group position-absolute w-100"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="glnFrom" class="form-label">GLN From</label>
                            <select class="form-select" name="gln_from" id="glnFrom">
                                <option selected>Choose...</option>
                                @foreach($glnName as $gln)
                                <option value="{{$gln}}">{{$gln}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="referenceNo" class="form-label">Reference No</label>
                            <input type="text" name="request_no" class="form-control" id="referenceNo"
                                placeholder="Enter reference number">
                        </div>
                        <div class="col-md-6">
                            <label for="glnTo" class="form-label">GLN To</label>
                            <select class="form-select" name="gln_to" id="glnTo">
                                <option selected>Choose...</option>
                                @foreach($glnName as $gln)
                                <option value="{{$gln}}">{{$gln}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <table class="table table-bordered" id="selectedProductsTable">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>GTIN/SKU</th>
                                        <th>Qty</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" name="note" id="notes" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm stockTransferSaveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
