<div class="modal" tabindex="-1" id="expire_date_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Expire Date</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <p id="p_expire_details"></p>
        <form action="{{ route('access_plan.change_expire') }}" method="post">
            @csrf
            <input type="hidden" name="exp_access_plan_id" id="exp_access_plan_id" value="0">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="expire_date" class="form-label">Expire Date</label>
                        <input type="date" class="form-control" id="expire_date" name="expire_date" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="expire_time" class="form-label">Expire Time</label>
                        <input type="time" class="form-control" id="expire_time" name="expire_time" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success float-end mt-3">Update Expire Date</button>
        </form>
      </div>

    </div>
  </div>
</div>
