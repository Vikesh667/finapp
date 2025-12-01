<?php echo view('header'); ?>
<?php
$role = session()->get('role');
?>

<body>
    <?php echo view('topHeader'); ?>
    <div id="appCapsule" class="full-height">
        <div class="user-container">
            <div class="user-list mt-5 mb-5">
                <div class="user-list-header premium-header">
                    <h5><ion-icon name="swap-horizontal-outline"></ion-icon> Transaction List</h5>

                    <div class="header-actions">
                        <a href="#" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addtransactionModal">
                            <ion-icon name="add-circle-outline"></ion-icon> Create Transaction
                        </a>
                    </div>
                </div>

                <form method="GET" class="card p-3 mb-3 shadow-sm border-0 filter-card">


                    <h6 class="fw-bold mb-3"><ion-icon name="funnel-outline"></ion-icon> Filter Transactions</h6>

                    <div class="row g-2 align-items-end">

                        <?php if (session()->get('role') === 'admin'): ?>
                            <!-- User -->
                            <div class="col-md-2 col-6">
                                <label class="form-label">User</label>
                                <select id="userSelect_transaction" name="user_id" class="form-select">
                                    <option value="">Select User</option>
                                    <?php foreach ($users as $u): ?>
                                        <option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                       

                        <!-- Client -->
                        <div class="col-md-2 col-6">
                            <label class="form-label">Client</label>
                            <select id="clientForTransactionsSelect" name="client_id" class="form-select">
                                <option value="">Select Client</option>
                                <?php if (session()->get('role') !== 'admin'): ?>
                                    <?php foreach ($clients as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= $c['company_name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <!-- Customer -->
                        <div class="col-md-2 col-6">
                            <label class="form-label">Customer</label>
                            <select id="customerTransactionsSelect" name="customer_id" class="form-select">
                                <option value="">Select Customer</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-2 col-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="paid" <?= ($filters['status'] == 'paid') ? 'selected' : '' ?>>Paid</option>
                                <option value="pending" <?= ($filters['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                            </select>
                        </div>

                        <!-- Date Filter -->
                        <div class="col-md-2 col-6">
                            <label class="form-label">Date</label>
                            <select id="dateFilter" name="date_filter" class="form-select">
                                <option value="">All Time</option>
                                <option value="today" <?= ($filters['date_filter'] == 'today') ? 'selected' : '' ?>>Today</option>
                                <option value="yesterday" <?= ($filters['date_filter'] == 'yesterday') ? 'selected' : '' ?>>Yesterday</option>
                                <option value="this_week" <?= ($filters['date_filter'] == 'this_week') ? 'selected' : '' ?>>This Week</option>
                                <option value="last_week" <?= ($filters['date_filter'] == 'last_week') ? 'selected' : '' ?>>Last Week</option>
                                <option value="this_month" <?= ($filters['date_filter'] == 'this_month') ? 'selected' : '' ?>>This Month</option>
                                <option value="last_month" <?= ($filters['date_filter'] == 'last_month') ? 'selected' : '' ?>>Last Month</option>
                                <option value="custom" <?= ($filters['date_filter'] == 'custom') ? 'selected' : '' ?>>Date Range</option>
                            </select>
                        </div>

                        <!-- From/To date (only when custom is selected) -->
                        <div class="col-md-2 col-6" id="fromDateBox" style="display: <?= ($filters['date_filter'] == 'custom') ? 'block' : 'none' ?>;">
                            <label class="form-label">From</label>
                            <input type="date" name="from_date" value="<?= $filters['from_date'] ?? '' ?>" class="form-control">
                        </div>

                        <div class="col-md-2 col-6" id="toDateBox" style="display: <?= ($filters['date_filter'] == 'custom') ? 'block' : 'none' ?>;">
                            <label class="form-label">To</label>
                            <input type="date" name="to_date" value="<?= $filters['to_date'] ?? '' ?>" class="form-control">
                        </div>

                        <!-- Filter / Reset -->
                        <div class="col-12 col-md-3 mt-2 d-flex gap-2 justify-content-md-end">
                            <button class="btn btn-primary flex-fill">
                                <ion-icon name="search-outline"></ion-icon> Apply
                            </button>
                            <a href="<?= (session()->get('role') === 'admin') ? base_url('admin/transaction-list') : base_url('user/transaction-list') ?>"
                                class="btn btn-secondary flex-fill">
                                <ion-icon name="refresh-outline"></ion-icon> Refresh
                            </a>
                        </div>

                    </div>

                </form>



                <div class="table-responsive">
                    <table id="example" class="table table-modern">
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
                            <?php if (isset($transactions) && !empty($transactions)): ?>
                                <?php
                                $start = 1 + ($pager->getCurrentPage() - 1) * $pager->getPerPage();
                                foreach ($transactions as $index => $transaction):
                                ?>
                                    <tr>
                                        <td data-label="Sr.No"><?= $start + $index ?></td>
                                        <td data-label="Customer Name"><?= esc($transaction['transfor_by']) ?></td>
                                        <td data-label="No Of Codes"><?= esc($transaction['code']) ?></td>
                                        <td data-label="Rate per code"><?= esc($transaction['rate']) ?></td>
                                        <td data-label="Free Code"><?= esc($transaction['extra_code']) ?></td>
                                        <td data-label="Total Amount"><?= esc($transaction['total_amount']) ?></td>
                                        <td data-label="Paid Amount"><?= esc($transaction['paid_amount']) ?></td>
                                        <td data-label="Remaining Amount"><?= esc($transaction['remaining_amount']) ?></td>
                                        <td data-label="Total Code"><?= esc($transaction['total_code']) ?></td>
                                        <td data-label="Date"><?= esc($transaction['created_at']) ?></td>
                                        <td data-label="Remark"><?= esc($transaction['remark']) ?></td>
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
                                            <div class="d-flex flex-wrap justify-content-center gap-2">

                                                <?php if ($transaction['remaining_amount'] > 0): ?>
                                                    <!-- Pay Now -->
                                                    <a href="#"
                                                        class="btn btn-sm btn-outline-primary edit-transaction"
                                                        data-id="<?= $transaction['id'] ?>"
                                                        title="Pay Now"
                                                        data-bs-toggle="tooltip">
                                                        <ion-icon name="card-outline"></ion-icon>
                                                    </a>
                                                <?php endif; ?>

                                                <!-- View -->
                                                <a href="#"
                                                    class="btn btn-sm btn-outline-info view-transaction"
                                                    data-view-id="<?= $transaction['id'] ?>"
                                                    title="View Transaction"
                                                    data-bs-toggle="tooltip">
                                                    <ion-icon name="eye-outline"></ion-icon>
                                                </a>

                                                <!-- Delete -->
                                                <?php
                                                $deleteUrl = ($role === 'admin')
                                                    ? base_url('admin/transaction-delete/' . $transaction['id'])
                                                    : base_url('user/transaction-delete/' . $transaction['id']);
                                                ?>
                                                <?php if ($transaction['remaining_amount'] == 0): ?>
                                                    <form method="post" action="<?= $deleteUrl ?>" onsubmit="return confirm('Are you sure?')">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Delete"
                                                            data-bs-toggle="tooltip">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                                <!-- Invoice -->
                                                <a href="<?= base_url('admin/invoice/preview/' . $transaction['id']) ?>"
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="Invoice"
                                                    data-bs-toggle="tooltip">
                                                    <ion-icon name="document-text-outline"></ion-icon>
                                                </a>

                                            </div>
                                        </td>


                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <?php echo view('transaction/addtransaction'); ?>

    <!-- Edit Transaction Modal -->
    <?php echo view('transaction/pay-now'); ?>
    <!-- Invoice Modal -->
    <?php echo view('transaction/transactionPaymentHistory'); ?>
    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>