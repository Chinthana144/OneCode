<div class="modal" tabindex="-1" role="dialog" id="customer_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Customer</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>

            <form id="frm_customer">
                @csrf
                <div class="modal-body">
                    <label for="" class="form-label">Customer Type</label>
                    <select name="cmb_customer_type" id="cmb_customer_type" class="form-select"></select>

                    <label for="" class="form-label mt-2">Full Name</label>
                    <input type="text" name="fullname" class="form-control" required>

                    <label for="" class="form-label mt-2">Phone</label>
                    <input type="text" name="phone" class="form-control" required>

                    <label for="" class="form-label mt-2">Email</label>
                    <input type="text" name="email" class="form-control">

                    <label for="" class="form-label mt-2">Username</label>
                    <input type="text" name="username" class="form-control" id="txt_add_username" required>

                    <label for="" class="form-label mt-2">Password</label>
                    <input type="password" name="password" class="form-control bi bi-eye-slash" required>

                    <label for="" class="form-label mt-2">Customer Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="chk_customer_stat" name="chk_customer_stat"
                            checked>
                        <label class="form-check-label" for="chk_customer_stat">Active
                            input</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Customer</button>
                    {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                </div>
            </form>

        </div>
    </div>
</div>
