  <div class="modal fade" id="payNowModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="payNowForm" method="post"
                action="<?= (session()->get('role') === 'admin')
                            ? base_url('admin/transaction/payNow')
                            : base_url('user/transaction/payNow') ?>">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-white">Pay Now</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="userId" name="user_id">
                        <input type="hidden" id="clientId" name="client_id">
                        <input type="hidden" id="customerId" name="customer_id">
                        <input type="hidden" id="transactionId" name="transaction_id">

                        <div class="mb-3">
                            <label>Customer Name</label>
                            <input type="text" id="customerName" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Total Amount</label>
                            <input type="text" id="totalAmount" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Remaining Amount</label>
                            <input type="text" id="remainingAmount" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Enter Payment Amount</label>
                            <input type="number" id="payAmount" name="pay_amount" class="form-control" min="1" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label>Payment Method</label>
                            <select id="paymentMethod" name="payment_method" class="form-control" required>
                                <option value="">Select Method</option>
                                <option value="cash">Cash Payment</option>
                                <option value="online">Online Payment</option>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Remark</label>
                            <input type="text" id="remark" name="remark" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <ion-icon name="card-outline"></ion-icon> Pay
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>