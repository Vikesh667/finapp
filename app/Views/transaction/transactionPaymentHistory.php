 <div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">

                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Invoice Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">

                    <!-- Header -->
                    <div class="text-center mb-3">
                        <img src="https://plus.unsplash.com/premium_photo-1682002135678-87b8a2fdde50?auto=format&fit=crop&q=80&w=735"
                            class="rounded-circle border"
                            style="width:90px;height:90px;object-fit:cover;">
                        <h4 class="fw-bold mt-2">Customer Invoice</h4>
                        <p class="text-muted mb-0">Transaction Summary</p>
                    </div>

                    <hr>

                    <!-- Details -->
                    <div class="row mb-4">

                        <div class="col-md-6">
                            <p><strong>Customer Name:</strong> <span id="customer_Name" class="text-primary"></span></p>
                            <p><strong>Code:</strong> <span id="transactionCode"></span></p>
                            <p><strong>Total Code:</strong> <span id="totalCode"></span></p>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="gstCheck">
                                <label for="gstCheck" class="form-check-label"><strong>Apply GST (18%)</strong></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <p><strong>Created Date:</strong> <span id="transactionDate"></span></p>
                            <p><strong>Extra Code:</strong> <span id="extraCode"></span></p>
                            <p><strong>Receipt No.:</strong> <span id="transaction_Id"></span></p>

                            <input type="text" id="gstNumber" class="form-control form-control-sm mt-2"
                                placeholder="Enter GST Number" style="display:none;">
                        </div>

                    </div>

                    <!-- Payment Summary -->
                    <div class="mb-4">
                        <h6 class="fw-semibold text-center">Payment Summary</h6>
                        <table class="table table-sm table-bordered text-center">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Remaining Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>₹<span id="total_Amount"></span></td>
                                    <td>₹<span id="paidAmount"></span></td>
                                    <td>₹<span id="remaining_Amount"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- GST Summary (Single 18%) -->
                    <div id="gstSection" style="display:none;">
                        <h6 class="fw-semibold text-center">GST Breakdown (18%)</h6>
                        <table class="table table-sm table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Base Amount</th>
                                    <th>GST (18%)</th>
                                    <th>Total After GST</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>₹<span id="baseAmount"></span></td>
                                    <td>₹<span id="gst"></span></td>
                                    <td>₹<span id="totalWithGST"></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- History -->
                    <div class="mt-3">
                        <h6 class="fw-semibold text-center">Payment History</h6>
                        <table class="table table-sm table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No.</th>
                                    <th>Before Paid Amount</th>
                                    <th>Amount</th>
                                    <th>After Paid Amount</th>
                                    <th>Payment Time</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody id="transactionHistory"></tbody>
                        </table>
                    </div>

                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer justify-content-between no-print">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary px-4" id="downloadReceipt">
                        <ion-icon name="download-outline"></ion-icon> Download PDF
                    </button>
                </div>

            </div>
        </div>
    </div>