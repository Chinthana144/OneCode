<div class="modal" tabindex="-1" role="dialog" id="customer_add_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="btn-close" id="btn_close_add_modal" data-dismiss="modal" aria-label="Close">
        </button>
      </div>

      <form action="{{ route('customer.store') }}" method="post">
        @csrf
        <div class="modal-body">
            <label for="" class="form-label">Select Camp</label>
            <select name="cmb_camp" class="form-select" id="cmb_camp">
                @foreach ($camps as $camp)
                    <option value="{{ $camp->id }}" @selected($camp->id == Session::get('active_camp_id'))> {{ $camp->name }}
                    </option>
                @endforeach
            </select>

            <label for="" class="form-label mt-2">Select Customer Type</label>
            <select name="cmb_customer_type" class="form-select" id="cmb_customer_type">
                @foreach ($customer_types as $customer_type)
                    <option value="{{ $customer_type->id }}"> {{ $customer_type->customerType }}
                    </option>
                @endforeach
            </select>

            <label for="" class="form-label mt-2">Full Name</label>
            <input type="text" name="fullname" class="form-control" required>

            <label for="" class="form-label mt-2">Phone</label>
            <input type="text" name="phone" id="txt_phone" class="form-control" placeholder="0#########" required>
            <span id="phone_error"><SMALL></SMALL></span>

            {{--<label for="" class="form-label mt-2">Email</label>
            <input type="text" name="email" class="form-control"> --}}

            {{-- <label for="" class="form-label mt-2">Username</label>
            <input type="text" name="username" class="form-control" id="txt_add_username"
                placeholder="0512345678" required> --}}

            <label for="" class="form-label mt-2">Password</label>
            <input type="password" name="password" class="form-control bi bi-eye-slash" placeholder="password"
                required>

            <label for="" class="form-label mt-2">Customer Status</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="chk_customer_stat" name="chk_customer_stat"
                    checked>
                <label class="form-check-label" for="chk_customer_stat">Active
                    input
                </label>
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
