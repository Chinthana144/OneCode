<div class="modal" tabindex="-1" role="dialog" id="packageEditModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" id="btn_close_package_edit">
          {{-- <span aria-hidden="true">&times;</span> --}}
        </button>
      </div>

      <form action="{{ route('packages.update') }}" method="post">
        @csrf
        @method('PUT')
        <input type="hidden" name="hide_package_id" id="hide_package_id" value="0">
      <div class="modal-body">
            <label for="" class="form-label">Select Customer Type</label>
            <select name="cmb_customer_type" class="form-select" id="cmb_edit_customer_type">
                @foreach ($customer_types as $customer_type)
                    <option value="{{ $customer_type->id }}"> {{ $customer_type->customerType }}
                    </option>
                @endforeach
            </select>

            <label for="" class="form-label mt-2">Package Name</label>
            <input type="text" name="name" id="edit_package_name" class="form-control">

            <label for="" class="form-label mt-2">Duration(days)</label>
            <input type="text" name="duration" id="edit_package_duration" class="form-control">

            <label for="" class="form-label mt-2">Price</label>
            <input type="number" step="0.01" name="price" id="edit_package_price" class="form-control">

            <label for="" class="form-label mt-2">Bandwidth</label>
            <input type="text" name="bandwidth" id="edit_package_bandwidth" class="form-control">

            <label for="" class="form-label mt-2">Download Limit (MB)</label>
            <input type="number" name="downloadlimit" id="edit_package_downloadlimit" class="form-control">

            <label for="" class="form-label mt-2">Upload Limit (MB)</label>
            <input type="number" name="uploadlimit" id="edit_package_uploadlimit" class="form-control">

            <label for="" class="form-label mt-2">Package Status</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="chk_package_stat" id="chk_package_edit_stat"
                    checked>
                <label class="form-check-label" for="chk_package_edit_stat">Active</label>
            </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update Package</button>
        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
      </div>
      </form>

    </div>
  </div>
</div>
