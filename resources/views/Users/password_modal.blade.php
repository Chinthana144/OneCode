<div class="modal" tabindex="-1" role="dialog" id="password_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Password Change</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>

            <form action="{{ route('users.updatepwd') }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="hide_user_id" id="hide_user_id">
                    <input type="hidden" name="hide_route" value="user">

                    <label for="">New Password</label>
                    <input type="password" name="password" id="pwd_change" class="form-control" required>

                    <label for="">Re-enter Password</label>
                    <input type="password" name="re_password" id="pwd_re_change" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn_pwd_change">Change Password</button>
                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                </div>
            </form>

        </div>
    </div>
</div>
