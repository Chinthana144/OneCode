<div class="modal" tabindex="-1" role="dialog" id="subs_edit_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Subscription</h5>
                <button type="button" class="btn-close" id="btn_close_edit_modal" data-dismiss="modal" aria-label="Close">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p id="p_sub_details">Customer Package</p>
                    </div>
                    <div class="col-md-6">
                        <p id="p_sub_dates">Subscription dates</p>
                    </div>
                </div>


                <form action="{{ route('customer.updateExpireDate') }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="hide_subscription_id" id="hide_subscription_id" value="0">
                    <input type="hidden" name="hide_customer_id" id="hide_customer_id" value="0">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="expire_date" class="form-label">Expire Date</label>
                                <input type="date" class="form-control" id="expire_date" name="expire_date" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="expire_time" class="form-label">Expire Time</label>
                                <input type="time" class="form-control" id="expire_time" name="expire_time" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary mt-4">Update Expire Date</button>
                        </div>
                    </div>
                </form>

                <div class="border border-success rounded mt-3 p-2">
                    <h5 id="h5_reset_mac">
                        Reset Subscription
                    </h5>
                    <form action="{{ route('subscription.reset') }}" method="post">
                        @csrf
                        <input type="hidden" name="reset_subscription_id" id="reset_subscription_id" value="0">
                        <input type="hidden" name="reset_customer_id" id="reset_customer_id" value="0">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="mac_address" name="mac_address" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success w-100">Reset Subscription</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="border border-danger rounded mt-3 p-2">
                    <h5 id="h5_reset_mac">
                        Cancel Subscription
                    </h5>
                    <form action="{{ route('subscription.cancel') }}" method="post">
                        @csrf
                        <input type="hidden" name="cancel_subscription_id" id="cancel_subscriptionID" value="0">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="cancel_mac_address" id="cancel_macAddress" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-danger w-100" id="btn_cancel_subscription" >Cancel Subscription</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="border border-primary rounded mt-3 p-2">
                    <h5>
                        Change Status
                    </h5>
                    <form action="{{ route('subscription.changeStatus') }}" method="post">
                        @csrf
                        <input type="hidden" name="status_subscription_id" id="status_subscription_id" value="0">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="cmb_status" id="cmb_status" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="2">Running</option>
                                    <option value="3">Expired</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">Change Status</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
