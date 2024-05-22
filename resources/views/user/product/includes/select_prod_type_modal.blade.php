<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addProductForm" action="" method="post">@csrf
                <div class="modal-body">

                    <div>
                        <label for="product_type" class="form-label">Product Type</label>
                        <select class="single-select form-control" name="product_type" id="product_type">
                            <option disabled selected>Choose...</option>
                            <option value="gs1">GS1</option>
                            <option value="non_gs1">Non GS1</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning rounded-0" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-block rounded-0"
                        style="width:80% !important; margin: auto; background-color: black; color: white;"><span
                            id="spinner" class="" role="status" aria-hidden="true"></span>&nbsp;&nbsp;<span
                            class="saveProductBtn">Create Product</span></button>
                </div>
            </form>

        </div>
    </div>
</div>
