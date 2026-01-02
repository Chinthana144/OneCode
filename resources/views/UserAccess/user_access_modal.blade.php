<!-- Bootstrap Modal -->
<div class="modal fade" id="user_access_modal" tabindex="-1" aria-labelledby="userAccessModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userAccessModalLabel">User Access</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('useraccess.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="campSelect" class="form-label">Camp</label>
                        <select class="form-select" id="cmb_camps" name="cmb_camps">
                            @foreach ($camps as $camp)
                                <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                            @endforeach
                            <!-- Add camp options here -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="userSelect" class="form-label">User</label>
                        <select class="form-select" id="cmb_users" name="cmb_users">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                            <!-- Add user options here -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pageSelect" class="form-label">Page</label>
                        <select class="form-select" id="cmb_pages" name="cmb_pages">
                            @foreach ($pages as $page)
                                <option value="{{ $page->id }}">{{ $page->pagename }}</option>
                            @endforeach
                            <!-- Add page options here -->
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="chk_create" id="chk_create">
                                <label class="form-check-label" for="chk_create">Create</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="chk_view" id="chk_view">
                                <label class="form-check-label" for="chk_view">View</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="chk_edit" id="chk_edit">
                                <label class="form-check-label" for="chk_edit">Edit</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="chk_delete" id="chk_delete">
                                <label class="form-check-label" for="chk_delete">Delete</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary float-end">Add Access</button>
                </form>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div> --}}
        </div>
    </div>
</div>
