<div class="modal fade" id="activityPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="activityForm" action="{{ route('login.member') }}">


            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Activity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Select Activity <font style="color: red;">*</font></label>
                    <select name="activity" id="memberLoginActivity"
                        class="form-control select2 cr_activitye memberLoginActivity" data-placeholder="select activity"
                        data-live-search="true" style="width: 100%;">
                        <option value="">-select-</option>
                    </select>
                    <input type="hidden" value="" name="email">
                </div>
                <div class="form-group">
                    <label>Password <font style="color: red;">*</font></label>
                    <input type="password" name="password" class="form-control">
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn loginActivityBtn" style="background-color: #044E75; color: white;">Login</button>
            </div>
            </form>
        </div>
    </div>
</div>
