<div class="modal" tabindex="-1" id="camp_change_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Camp Transfer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <p id="p_transfer_details"></p>
        <form action="{{ route('access_plan.camp_transfer') }}" method="post">
            @csrf
            <input type="hidden" name="ch_access_plan_id" id="ch_access_plan_id">
            <label for="">Camps</label>
            <select name="cmb_camp" id="cmb_camp" class="form-select">
                @foreach ($camps as $camp)
                    <option value="{{ $camp->id }}">{{ $camp->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-success float-end mt-3">Transfer</button>
        </form>
      </div>

    </div>
  </div>
</div>
