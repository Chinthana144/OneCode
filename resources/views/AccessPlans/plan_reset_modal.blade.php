<div class="modal" tabindex="-1" id="plan_reset_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Subscription/Voucher Edit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <p id="p_details">Package details goes here.</p>

        <form action="{{ route('access_plan.reset_status') }}" method="post">
            @csrf
            <input type="hidden" name="camp_id" id="camp_id">
            <input type="hidden" name="access_plan_id" id="access_plan_id">

            <button type="submit" name="action" value="reset" class="btn btn-success">Reset MAC Address</button>
            <button type="submit" name="action" value="cancel" class="btn btn-danger float-end">Cancel Package</button>
        </form>
      </div>

    </div>
  </div>
</div>
