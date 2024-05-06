<!-----------Update Password Modal ----------------->
<div class="modal fade" id="updateCurrentUserPassModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="currentUserChangePassForm">
                <div class="modal-body">
                    <div class="mb-3 col-md-12">
                        <label for="current_pass" class="form-label">Current Password <font style="color: red;">*</font>
                        </label>
                        <input type="password" class="form-control" id="current_pass" name="current_pass"
                            placeholder="current password">

                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="new_pass" class="form-label">New Password <font style="color: red;">*</font></label>
                        <input type="password" class="form-control" id="new_pass" name="new_pass"
                            placeholder="new password">

                    </div>
                    <div class="mb-3 col-md-12">
                        <label for="confirm_pass" class="form-label">Confirm Password <font style="color: red;">*</font>
                        </label>
                        <input type="password" class="form-control" id="confirm_pass" name="confirm_password"
                            placeholder="confirm password">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-block rounded-0"
                        style="width:80% !important; margin: auto; background-color: black; color: white;"><span
                            id="spinner" class="" role="status" aria-hidden="true"></span>&nbsp;&nbsp;<span
                            class="updateCurrentUserPassBtn">Update Password</span></button>
                </div>
            </form>

        </div>
    </div>
</div>