<div class="modal" tabindex="-1" role="dialog" id="campuser_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Camp Users</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" id="btn_close_category">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>

            <form action="{{ route('campusers.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <label for="" class="form-label">Select Camp</label>
                    <select name="cmb_camps" id="cmb_camps" class="form-select">
                        @foreach ($camps as $camp)
                            <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                        @endforeach
                    </select>

                    <label for="" class="form-label mt-2">Select Users</label>
                    <select name="cmb_users" id="cmb_users" class="form-select">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="btn_add_campuser" class="btn btn-primary">Add
                        User</button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
