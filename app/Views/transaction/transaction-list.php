<?php echo view('header'); ?>

<body>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <img src="assets/img/logo.png" alt="logo" class="logo">
        </div>
        <div class="right">
            <?php
            $session = session();
            $role = $session->get('role');
            $userName = $session->get('user_name');
            $profileImage = $session->get('profile_image');
            $firstLetter = strtoupper(substr($userName, 0, 1));
            ?>

            <?php if ($role === 'admin'): ?>
                <!-- Admin: Notifications + Avatar -->
                <a href="<?= base_url('admin/app-notifications') ?>" class="headerButton">
                    <ion-icon class="icon" name="notifications-outline"></ion-icon>
                    <span class="badge badge-danger">4</span>
                </a>

                <a href="<?= base_url('admin/app-settings') ?>" class="headerButton">
                    <?php if (!empty($profileImage) && file_exists(FCPATH . 'assets/uploads/logos' . $profileImage)): ?>
                        <img
                            src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
                            alt="avatar"
                            class="rounded-circle shadow"
                            style="width:32px; height:32px; object-fit:cover; object-position:center;">
                    <?php else: ?>
                        <div class="avatar-fallback bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px; font-weight: 600;">
                            <?= esc($firstLetter) ?>
                        </div>
                    <?php endif; ?>
                </a>

            <?php elseif ($role === 'user'): ?>
                <!-- User: Only Avatar/Profile -->
                <a href="<?= base_url('admin/app-notifications') ?>" class="headerButton">
                    <ion-icon class="icon" name="notifications-outline"></ion-icon>
                    <span class="badge badge-danger">4</span>
                </a>
                <a href="<?= base_url('user/app-settings') ?>" class="headerButton">
                    <?php if (!empty($profileImage) && file_exists(FCPATH . 'assets/uploads/logos/' . $profileImage)): ?>
                        <img
                            src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
                            alt="avatar"
                            class="rounded-circle shadow"
                            style="width: 32px; height: 32px; object-fit:cover; object-position:center;">
                    <?php else: ?>
                        <div class="avatar-fallback bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px; font-weight: 600;">
                            <?= esc($firstLetter) ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>


    </div>
    <div id="appCapsule" class="full-height">
        <div class="user-container">
            <div class="user-list mt-5 mb-5">
                <div class="user-list-header">
                    <h5>transaction List</h5>
                    <div class="right-section">
                        <div class="add">
                            <a href="#" class="button" data-bs-toggle="modal" data-bs-target="#addtransactionModal">
                                <ion-icon name="add-outline"></ion-icon>
                                <span>Create Transaction</span>
                            </a>
                        </div>
                    </div>
                </div>
                <form method="GET" class="card p-3 mb-3 shadow-sm">
                    <div class="row">
                        <?php if ($role = session()->get('role') === 'admin'): ?>
                            <div class="col-md-2 col-6 mb-2">
                                <label>Client</label>
                                <select id="clientForTransactionsSelect" name="client_id" class="form-select">
                                    <option value="">Select Client</option>
                                    <?php foreach ($clients as $c): ?>
                                        <option value="<?= $c['id'] ?>" style=""><?= esc($c['company_name']) ?>(<?= $c['name'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>

                            </div>

                            <div class="col-md-2 col-6 mb-2">
                                <label>Customer</label>
                                <select id="customerTransactionsSelect" name="customer_id" class="form-select">
                                    <option value="">Select Customer</option>
                                </select>
                            </div>
                        <?php else: ?>
                            <div class="col-md-2 col-6 mb-2">
                                <label>Client</label>
                                <select id="customerTransactionsSelect" name="customer_id" class="form-select">
                                    <option value="">Select Client</option>
                                    <?php foreach ($customers as $c): ?>
                                        <option value="<?= $c['id'] ?>" style=""><?= $c['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-2 col-6 mb-2">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="paid" <?= ($filters['status'] == 'paid') ? 'selected' : '' ?>>Paid</option>
                                <option value="pending" <?= ($filters['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-6 mb-2">
                            <label>Min Amount</label>
                            <input type="number" name="min_amount" class="form-control" value="<?= $filters['min_amount'] ?>">
                        </div>

                        <div class="col-md-2 col-6 mb-2">
                            <label>Max Amount</label>
                            <input type="number" name="max_amount" class="form-control" value="<?= $filters['max_amount'] ?>">
                        </div>

                        <div class="col-md-2 col-6 mb-2">
                            <label>License Key</label>
                            <input type="text" name="license_key" class="form-control" value="<?= $filters['license_key'] ?>">
                        </div>

                        <div class="col-md-3 col-6 mb-2">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="<?= $filters['from_date'] ?>">
                        </div>

                        <div class="col-md-3 col-6 mb-2">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="<?= $filters['to_date'] ?>">
                        </div class="col-md-3 col-6 mb-2">
                        <div>
                            <button class="btn btn-primary mt-2">Filter</button>

                            <a href="<?= (session()->get('role') === 'admin') ? base_url('admin/transaction-list') : base_url('user/transaction-list') ?>" class="btn btn-secondary mt-2">Reset</a>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table id='example' class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Customer Name</th>
                                <th>No Of Codes</th>
                                <th>Rate per code</th>
                                <th>Free Code(CN)</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Remaining Amount</th>
                                <th>Total Code</th>
                                <th>Date</th>
                                <th>Remark</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions)): ?>
                                <?php
                                $start = 1 + ($pager->getCurrentPage() - 1) * $pager->getPerPage();
                                foreach ($transactions as $index => $transaction):
                                ?>
                                    <tr>
                                        <td><?= $start + $index ?></td>
                                        <td><?= esc($transaction['transfor_by']) ?></td>
                                        <td><?= esc($transaction['code']) ?></td>
                                        <td><?= esc($transaction['rate']) ?></td>
                                        <td><?= esc($transaction['extra_code']) ?></td>
                                        <td><?= esc($transaction['total_amount']) ?></td>
                                        <td><?= esc($transaction['paid_amount']) ?></td>
                                        <td><?= esc($transaction['remaining_amount']) ?></td>
                                        <td><?= esc($transaction['total_code']) ?></td>
                                        <td><?= esc($transaction['created_at']) ?></td>
                                        <td><?= esc($transaction['remark']) ?></td>
                                        <td>
                                            <?php
                                            $paid = $transaction['total_amount'] - $transaction['remaining_amount'];
                                            $percentPaid = ($paid / $transaction['total_amount']) * 100;
                                            $percentPaid = round($percentPaid, 2);

                                            $percentRemaining = 100 - $percentPaid;
                                            ?>

                                            <div class="progress" style="height: 10px;">

                                                <!-- PAID PART (Green) -->
                                                <?php if ($percentPaid > 0): ?>
                                                    <div class="progress-bar bg-success"
                                                        style="width: <?= $percentPaid ?>%;">
                                                    </div>
                                                <?php endif; ?>

                                                <!-- REMAINING PART (Red) -->
                                                <?php if ($percentRemaining > 0 && $percentPaid < 100): ?>
                                                    <div class="progress-bar bg-danger"
                                                        style="width: <?= $percentRemaining ?>%;">
                                                    </div>
                                                <?php endif; ?>

                                            </div>

                                            <small><?= $percentPaid ?>% Paid</small>
                                        </td>



                                        <td class="text-center">
                                            <div>
                                                <?php if ($transaction['remaining_amount'] > 0): ?>
                                                    <a href="#"
                                                        class="btn btn-primary edit-transaction"
                                                        data-id="<?= $transaction['id'] ?>">
                                                        <ion-icon name="card-outline"></ion-icon></a>
                                                    </a>
                                                <?php endif; ?>
                                                <a href=""
                                                    class="btn btn-info view-transaction mt-3"
                                                    data-view-id="<?= $transaction['id'] ?>">
                                                    <ion-icon name="eye-outline"></ion-icon></a>
                                            </div>
                                            <?php
                                            $deleteUrl = ($role === 'admin')
                                                ? base_url('admin/transaction-delete/' . $transaction['id'])
                                                : base_url('user/transaction-delete/' . $transaction['id']);
                                            ?>
                                            <form method="post" action="<?= $deleteUrl ?>" style="display:inline;">
                                                <?php if ($transaction['remaining_amount'] == 0): ?>
                                                    <button type="submit" class="btn-icon delete" onclick="return confirm('Are you sure?')">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                            <a href="<?= base_url('admin/invoice/preview/' . $transaction['id']) ?>"
                                                class="btn btn-warning btn-sm">
                                                Generate Invoice
                                            </a>


                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <!-- FIXED: colspan should match total <th> count (11) -->
                                    <td colspan="11" class="text-center text-muted py-3">No transaction found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
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
                                        <select name="user_id" id="userSelect" class="form-control" required>
                                            <option value="">Loading users...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group basic mb-3">
                                    <label class="label">Select Client</label>
                                    <div class="input-group">
                                        <select name="client_id" id="clientSelectt" class="form-control" required>
                                            <option value="">Select user first</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group basic mb-3">
                                    <label class="label">Select Customer</label>
                                    <div class="input-group">
                                        <select name="customer_id" id="customerSelect" class="form-control" required>
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
                                        <select name="client_id" id="clientSelectt" class="form-control" required>
                                            <option value="">Loading clients...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group basic mb-3">
                                    <label class="label">Select Customer</label>
                                    <div class="input-group">
                                        <select name="customer_id" id="customerSelect" class="form-control" required>
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
                                    <input class="form-check-input" type="radio" name="gst_applied" id="gst_yes" value="1" required>
                                    <label class="form-check-label" for="gst_yes">With GST</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gst_applied" id="gst_no" value="0" required>
                                    <label class="form-check-label" for="gst_no">Without GST</label>
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

    <!-- Edit Transaction Modal -->
    <div class="modal fade" id="payNowModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="payNowForm" method="post"
                action="<?= ($role === 'admin')
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
    <!-- Invoice Modal -->
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


    <script>
        document.getElementById('clientForTransactionsSelect').addEventListener('change', function() {
            let clientId = this.value;

            fetch(`http://localhost/finapp/admin/get-customers/${clientId}`)
                .then(response => response.json())
                .then(data => {
                    let dropdown = document.getElementById('customerTransactionsSelect');
                    dropdown.innerHTML = '<option value="">Select Customer</option>';

                    data.forEach(c => {
                        dropdown.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                    });
                });

        });

    </script>




    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>