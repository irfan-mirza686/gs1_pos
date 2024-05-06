<!-----------Update Password Modal ----------------->
<div class="modal fade" id="updateUserPassModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="userUpdatePassForm" action="">
                <div class="modal-body">

                    <div class="mb-3 col-md-12">
                        <label for="password" class="form-label">New Password <font style="color: red;">*</font></label>
                        <input type="text" class="form-control" id="password" name="password"
                            placeholder="Password" required>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-block rounded-0"
                        style="width:80% !important; margin: auto; background-color: black; color: white;"><span
                            id="spinner" class="" role="status" aria-hidden="true"></span>&nbsp;&nbsp;<span
                            class="updateUserPassBtn">Update Password</span></button>
                </div>
            </form>

        </div>
    </div>
</div>