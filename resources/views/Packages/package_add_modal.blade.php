<div class="modal" tabindex="-1" role="dialog" id="packageAddModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" id="btn_close_package_add">
          {{-- <span aria-hidden="true">&times;</span> --}}
        </button>
      </div>

      <form action="{{ route('packages.store') }}" method="post">
      @csrf
      <div class="modal-body">
            <label for="" class="form-label">Select Customer Type</label>
            <select name="cmb_customer_type" class="form-select" id="cmb_customer_type">
                @foreach ($customer_types as $customer_type)
                    <option value="{{ $customer_type->id }}"> {{ $customer_type->customerType }}
                    </option>
                @endforeach
            </select>

            <label for="" class="form-label mt-2">Package Name</label>
            <input type="text" name="name" class="form-control">

            <label for="" class="form-label mt-2">Duration(days)</label>
            <input type="text" name="duration" class="form-control">

            <label for="" class="form-label mt-2">Price</label>
            <input type="number" step="0.01" name="price" class="form-control">

            <label for="" class="form-label mt-2">Bandwidth</label>
            <input type="text" name="bandwidth" class="form-control">

            <label for="" class="form-label mt-2">Download Limit (MB)</label>
            <input type="number" name="downloadlimit" class="form-control">

            <label for="" class="form-label mt-2">Upload Limit (MB)</label>
            <input type="number" name="uploadlimit" class="form-control">

            <label for="" class="form-label mt-2">Package Status</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="chk_package_stat" id="chk_package_stat"
                    checked>
                <label class="form-check-label" for="chk_package_stat">Active</label>
            </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Add Package</button>
        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
      </div>
      </form>

    </div>
  </div>
</div>
