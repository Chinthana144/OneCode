<div class="modal" tabindex="-1" role="dialog" id="edit_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>

            <form action="{{ route('users.update') }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="hide_edit_user_id" id="hide_edit_user_id">
                    <label for="">Select User Role</label>
                    <select name="cmb_role" id="cmb_edit_role" class="form-select">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>

                    <label for="">Username</label>
                    <input type="text" name="edit_name" id="edit_name" class="form-control">

                    <label for="">Email</label>
                    <input type="email" name="edit_email" id="edit_email" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                </div>
            </form>

        </div>
    </div>
</div>
