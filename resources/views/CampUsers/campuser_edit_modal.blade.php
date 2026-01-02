<div class="modal" tabindex="-1" role="dialog" id="campuser_edit_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Camp User Edit</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>

            <form action="{{ route('campusers.update') }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" name="hide_campuser_id" id="hide_campuser_id">
                <div class="modal-body">
                    <P id="p_campuser"></P>
                    <label for="" class="form-label">Select Camp</label>
                    <select name="cmb_edit_camps" id="cmb_edit_camps" class="form-select">
                        @foreach ($camps as $camp)
                            <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                        @endforeach
                    </select>

                    <label for="" class="form-label mt-2">Select Users</label>
                    <select name="cmb_edit_users" id="cmb_edit_users" class="form-select">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
