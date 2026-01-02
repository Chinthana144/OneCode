<div class="modal" tabindex="-1" role="dialog" id="register_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Register</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>
            <form action="{{ route('users.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <label for="">Select User Role</label>
                    <select name="cmb_role" id="cmb_role" class="form-select">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>

                    <label for="">Username</label>
                    <input type="text" name="name" class="form-control">

                    <label for="">Email</label>
                    <input type="email" name="email" class="form-control">

                    <label for="">Password</label>
                    <input type="password" name="password" id="password" class="form-control">

                    <label for="">Re-enter Password</label>
                    <input type="password" name="re_password" id="re_password" class="form-control">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Register</button>
                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                </div>
            </form>
        </div>
    </div>
</div>
