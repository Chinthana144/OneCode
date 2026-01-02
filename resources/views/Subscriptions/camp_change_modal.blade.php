<div class="modal" tabindex="-1" role="dialog" id="camp_change_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Camp</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" id="btn_close_camp_change">
          {{-- <span aria-hidden="true">&times;</span> --}}
        </button>
      </div>

      <form action="{{ route('subscription.changeCamp') }}" method="post">
        @csrf
        <input type="hidden" name="hide_change_subscription_id" id="hide_change_subscription_id" value="0">
        <div class="modal-body">
          <p class="text-danger">Note: <b>when camp is changed, subscription and the customer will be transferred to the camp.</b></p>

          <div class="mb-3">
            <p id="p_change_sub_details">apple</p>
          </div>

          <div class="mb-3">
              <label for="">Select Camp</label>
              <select name="cmb_camp" id="cmb_camp" class="form-select">
                  @foreach ($all_camps as $camp)
                      <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                  @endforeach
              </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Change Camp</button>
          {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
        </div>
      </form>

    </div>
  </div>
</div>