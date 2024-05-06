<div class="modal fade" id="updateItemPriceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateItemPriceForm" action="">
                <input type="hidden" name="product_id" value="">
                <div class="modal-body">
                    <div>
                        <label for="selling_price" class="form-label">Type</label>
                        <select class="form-select form-select-sm mb-3" name="type"
                            aria-label=".form-select-sm example">
                            <option selected="" value="">-select-</option>
                            <option value="new">New</option>
                            <option value="used">Used</option>
                        </select>
                        <label for="barcode" class="form-label">Barcode</label>
                        <input class="form-control form-control-sm mb-3" name="barcode" type="text"
                            placeholder="Update Barcode" />

                        <label for="selling_price" class="form-label">Selling Price</label>
                        <input class="form-control form-control-sm mb-3" name="selling_price" type="text"
                            placeholder="Enter Selling Price" />
                    </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-warning rounded-0" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-block rounded-0"
                            style="width:80% !important; margin: auto; background-color: black; color: white;"><span
                                id="item-spinner" class="" role="status" aria-hidden="true"></span>&nbsp;&nbsp;<span
                                class="saveItemPriceBtn">Update</span></button>
                    </div>
            </form>

        </div>
    </div>
</div>
