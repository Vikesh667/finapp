<div class="modal fade action-sheet" id="addtransactionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="action-sheet-content">
                        <?php
                        $role = session()->get('role');
                        $userId = session()->get('user_id');

                        $actionUrl = ($role === 'admin')
                            ? base_url('admin/transaction/add')
                            : base_url('user/transaction/add');
                        ?>

                        <form action="<?= $actionUrl ?>" method="post" id="addCustomerForm">

                            <!-- ADMIN ROLE -->
                            <?php if ($role === 'admin'): ?>
                                <div class="form-group basic mb-3">
                                    <label class="label">Select User</label>
                                    <div class="input-group">
                                        <select name="user_id" id="userSelect_transaction" class="form-control" required>
                                            <option value="">Loading users...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group basic mb-3">
                                    <label class="label">Select Client</label>
                                    <div class="input-group">
                                        <select name="client_id" id="clientSelect_transaction" class="form-control" required>
                                            <option value="">Select user first</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group basic mb-3">
                                    <label class="label">Select Customer</label>
                                    <div class="input-group">
                                        <select name="customer_id" id="customerSelect_transaction" class="form-control" required>
                                            <option value="">Select user first</option>
                                        </select>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!--  USER ROLE -->
                                <!-- Hidden input for logged-in user -->
                                <input type="hidden" name="user_id" value="<?= $userId ?>">

                                <div class="form-group basic mb-3">
                                    <label class="label">Select Client</label>
                                    <div class="input-group">
                                        <select name="client_id" id="clientSelect_transaction" class="form-control" required>
                                            <option value="">Loading clients...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group basic mb-3">
                                    <label class="label">Select Customer</label>
                                    <div class="input-group">
                                        <select name="customer_id" id="customerSelect_transaction" class="form-control" required>
                                            <option value="">Select user first</option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!--  COMMON FIELDS -->
                            <div class="form-group basic mb-3">
                                <label class="label">No of codes</label>
                                <div class="input-group">
                                    <input type="number"
                                        min="0"
                                        step="0.01"
                                        value="0"
                                        name="code" class="form-control" oninput="calculateTotalAmount()" placeholder="Enter code" required id="noOfCodes">
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Rate (Cost per code)</label>
                                <div class="input-group">
                                    <input type="number" name="rate" class="form-control" oninput="calculateTotalAmount()" placeholder="Cost Per code" required id="ratePerCode">
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Total Amount</label>
                                <div class="input-group">
                                    <input type="number" name="total_amount" class="form-control" placeholder="Enter amount" required id="totalAmounts">
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Paid Amount</label>
                                <div class="input-group">
                                    <input type="number" name="paid_amount" class="form-control" oninput="calculateTotalAmount()" placeholder="Paid amount" required id="paidAmounts">
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Remaining Amount</label>
                                <div class="input-group">
                                    <input type="number" name="remaining_amount" min="0" class="form-control" placeholder="Remaining amount" required id="remainingAmounts">
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Number of extra code</label>
                                <div class="input-group">
                                    <input type="number" name="extra_code" min='0' class="form-control" oninput="calculateTotalAmount()" placeholder="No of extra code" required id="extraCodes">
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Total Code</label>
                                <div class="input-group">
                                    <input type="number" name="total_code" class="form-control" placeholder="Total Code" required id="totalCodes">
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label">Select Selling Company</label>
                                <select id="companySelect" name="company_id" class="form-control" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label">Select HSN/ASC Code</label>
                                <select id="hsnSelect" name="hsn_code" class="form-control" required>
                                    <option value="">Loading...</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label d-block">GSTIN</label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gst_applied" id="gst_igst" value="igst" required>
                                    <label class="form-check-label" for="gst_igst">With IGST</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gst_applied" id="gst_cgst_sgst" value="cgst_sgst" required>
                                    <label class="form-check-label" for="gst_cgst_sgst">With (CGST (9%) + SGST (9%))</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gst_applied" id="gst_none" value="none" required>
                                    <label class="form-check-label" for="gst_none">Without GST</label>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">Remark</label>
                                <div class="input-group">
                                    <input type="text" name="remark" class="form-control" placeholder="Remark" required id="remark">
                                </div>
                            </div>
                            <div class="form-group basic mt-4">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <ion-icon name="person-add-outline"></ion-icon> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!--  JAVASCRIPT -->


            </div>
        </div>
    </div>