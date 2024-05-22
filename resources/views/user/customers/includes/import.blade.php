<!-------------------- dfhajflkajdflkajdklfjakldjfkladsf------------->
<div class="modal fade" id="importNewCustomersModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importCustomerForm" action="{{route('import.customers')}}" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group col-md-12">

                        <label for="">Import<font style="color: red;">*</font></label>
                        <input type="file" id="file" name="file" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning rounded-0" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-block rounded-0"
                        style="width:80% !important; margin: auto; background-color: #000080; color: white;"><span
                            id="spinner" class="" role="status" aria-hidden="true"></span>&nbsp;&nbsp;<span
                            class="importBtn">Import</span></button>
                </div>
            </form>

        </div>
    </div>
</div>
