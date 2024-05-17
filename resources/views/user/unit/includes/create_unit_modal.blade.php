<div class="modal fade" id="unitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUnitForm" action="">
                <div class="modal-body">
                <input type="hidden" name="unit_id">
                    <label for="exampleDataList" class="form-label">Unit Name</label>
                    <input class="form-control form-control-sm mb-3" name="name" type="text" placeholder="Enter Unit Name"
                        aria-label=".form-control-sm example">
                    <label for="exampleDataList" class="form-label">Status</label>
                    <select class="form-select form-select-sm mb-3" name="status" aria-label=".form-select-sm example">
                        <option selected="" value="">-select-</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning rounded-0" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-block rounded-0" style="width:80% !important; margin: auto; background-color: black; color: white;"><span id="spinner" class="" role="status" aria-hidden="true"></span>&nbsp;&nbsp;<span class="saveUnitBtn">Create Unit</span></button>
                </div>
            </form>

        </div>
    </div>
</div>