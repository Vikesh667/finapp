<?php echo view('header'); ?>
   <?php 
    $role=session()->get('role');
    ?>
<body>
    <?php echo view('topHeader');?>
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
                        <?php if (session()->get('role') === 'admin'): ?>

                            <!-- 1️⃣ Select User -->
                            <div class="col-md-2 col-6 mb-2">
                                <label>User</label>
                                <select id="userSelect_transaction" name="user_id" class="form-select">
                                    <option value="">Select User</option>
                                    <?php foreach ($users as $u): ?>
                                        <option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- 2️⃣ Select Client -->
                            <div class="col-md-2 col-6 mb-2">
                                <label>Client</label>
                                <select id="clientForTransactionsSelect" name="client_id" class="form-select">
                                    <option value="">Select Client</option>
                                </select>
                            </div>

                            <!-- 3️⃣ Select Customer -->
                            <div class="col-md-2 col-6 mb-2">
                                <label>Customer</label>
                                <select id="customerTransactionsSelect" name="customer_id" class="form-select">
                                    <option value="">Select Customer</option>
                                </select>
                            </div>

                        <?php else: ?>

                            <!-- USER LOGIN VIEW (only select client → customer auto filter) -->
                            <div class="col-md-2 col-6 mb-2">
                                <label>Client</label>
                                <select id="clientForTransactionsSelect" name="client_id" class="form-select">
                                    <option value="">Select Client</option>
                                    <?php foreach ($clients as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= $c['company_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2 col-6 mb-2">
                                <label>Customer</label>
                                <select id="customerTransactionsSelect" name="customer_id" class="form-select">
                                    <option value="">Select Customer</option>
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

                        <!-- ⭐ Date Filter UI (Same as screenshot) -->
                        <div class="col-md-2 col-6 mb-2">
                            <label>Date Filter</label>
                            <select id="dateFilter" name="date_filter" class="form-select">
                                <option value="">All Time</option>
                                <option value="today" <?= (!empty($filters['date_filter']) && $filters['date_filter'] == 'today') ? 'selected' : '' ?>>Today</option>
                                <option value="yesterday" <?= (!empty($filters['date_filter']) && $filters['date_filter'] == 'yesterday') ? 'selected' : '' ?>>Yesterday</option>
                                <option value="this_week" <?= (!empty($filters['date_filter']) && $filters['date_filter'] == 'this_week') ? 'selected' : '' ?>>Current Week</option>
                                <option value="last_week" <?= (!empty($filters['date_filter']) && $filters['date_filter'] == 'last_week') ? 'selected' : '' ?>>Previous Week</option>
                                <option value="this_month" <?= (!empty($filters['date_filter']) && $filters['date_filter'] == 'this_month') ? 'selected' : '' ?>>Current Month</option>
                                <option value="last_month" <?= (!empty($filters['date_filter']) && $filters['date_filter'] == 'last_month') ? 'selected' : '' ?>>Previous Month</option>
                                <option value="custom" <?= (!empty($filters['date_filter']) && $filters['date_filter'] == 'custom') ? 'selected' : '' ?>>Date Range</option>
                            </select>
                            <div class="col-md-12 mt-2">
                                <button class="btn btn-primary">Filter</button>
                                <a href="<?= (session()->get('role') === 'admin') ? base_url('admin/transaction-list') : base_url('user/transaction-list') ?>"
                                    class="btn btn-secondary">Reset</a>
                            </div>
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
                            <?php if (isset($transactions) && !empty($transactions)): ?>
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
                                            <div class="actionButton">
                                                <?php if ($transaction['remaining_amount'] > 0): ?>
                                                    <a href="#"
                                                        class="btn btn-primary edit-transaction"
                                                        data-id="<?= $transaction['id'] ?>">
                                                        <ion-icon name="card-outline"></ion-icon>
                                                    </a>
                                                <?php endif; ?>

                                                <a href=""
                                                    class="btn btn-info view-transaction mt-3"
                                                    data-view-id="<?= $transaction['id'] ?>">
                                                    <ion-icon name="eye-outline"></ion-icon>
                                                </a>

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
                                                    class="btn btn-warning btn-sm mt-3">
                                                    Invoice
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
    <?php echo view('transaction/addtransaction');?>

    <!-- Edit Transaction Modal -->
    <?php echo view('transaction/pay-now');?>
    <!-- Invoice Modal -->
     <?php echo view('transaction/transactionPaymentHistory');?>
    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>