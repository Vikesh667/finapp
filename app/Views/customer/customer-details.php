<?php echo view('header'); ?>

<body>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="logo" class="logo">
        </div>
        <div class="right">
            <?php
            $session = session();
            $role = $session->get('role');
            $userName = $session->get('user_name');
            $profileImage = $session->get('profile_image');
            $firstLetter = strtoupper(substr($userName, 0, 1));
            ?>

            <?php if ($role === 'admin' || $role === 'user'): ?>
                <a href="<?= base_url($role . '/app-notifications') ?>" class="headerButton">
                    <ion-icon class="icon" name="notifications-outline"></ion-icon>
                    <span class="badge badge-danger">4</span>
                </a>
                <a href="<?= base_url($role . '/app-settings') ?>" class="headerButton">
                    <?php if (!empty($profileImage) && file_exists(FCPATH . 'assets/uploads/logos/' . $profileImage)): ?>
                        <img src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
                            alt="avatar"
                            class="rounded-circle shadow"
                            style="width: 32px; height: 32px; object-fit: cover; object-position: center;">
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

    <div id="appCapsule" class="pt-5 pb-4" style="margin-top: 60px;">

        <div class="section mt-2">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="<?= base_url('assets/uploads/customers/avatar.png') ?>"
                            alt="Customer"
                            class="rounded-circle border border-primary"
                            style="width: 70px; height: 70px; object-fit: cover;">
                        <div class="ms-3">
                            <h4 class="mb-0"><?= esc($customer['name']) ?></h4>
                            <p class="mb-1 text-muted"><?= esc($customer['email']) ?></p>
                            <p class="small text-muted"><ion-icon name="call-outline"></ion-icon> <?= esc($customer['phone']) ?></p>
                        </div>
                        <div class="ms-auto">
                            <a href="<?= base_url('customer/edit/' . $customer['id']) ?>" class="btn btn-primary ">
                                <ion-icon name="create-outline"></ion-icon> Edit
                            </a>
                            <a href="<?= base_url('customer/pdf/' . $customer['id']) ?>" class="btn btn-success">
                                <ion-icon name="download-outline"></ion-icon> PDF
                            </a>
                        </div>
                    </div>
                    <hr>
                    <p><strong>Address:</strong> <?= esc($customer['address'] ?? 'N/A') ?></p>
                </div>
            </div>
        </div>

        <div class="section mt-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Transaction History</h5>
                    <ion-icon name="receipt-outline"></ion-icon>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($transactions)): ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Rate</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Remaining Amount</th>
                                        <th>Free Code</th>
                                        <th>Total Code</th>
                                        <th>Transaction Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalCode = 0;
                                    $totalAmount = 0;
                                    $totalPaid = 0;
                                    $totalRemaining = 0;
                                    $totalExtraCode = 0;
                                    ?>

                                    <?php foreach ($transactions as $index => $t): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($t['code']) ?></td>
                                            <td><?= esc($t['rate']) ?></td>
                                            <td>₹<?= number_format($t['total_amount'], 2) ?></td>
                                            <td>₹<?= number_format($t['paid_amount'], 2) ?></td>
                                            <td>₹<?= number_format($t['remaining_amount'], 2) ?></td>
                                            <td><?= esc($t['extra_code']) ?></td>
                                            <td><?= esc($t['total_code']) ?></td>
                                            <td><?= esc($t['created_at']) ?></td>
                                        </tr>

                                        <?php
                                        $totalCode       += (float)$t['code'];
                                        $totalAmount     += (float)$t['total_amount'];
                                        $totalPaid       += (float)$t['paid_amount'];
                                        $totalRemaining  += (float)$t['remaining_amount'];
                                        $totalExtraCode  += (float)$t['extra_code'];
                                        ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- ✅ Summary Section -->
                        <div class="p-3 border-top bg-light">
                            <h6 class="fw-bold mb-2">Transaction Summary</h6>
                            <div class="row text-center">
                                <div class="col-md-2 col-6">
                                    <div class="p-2 bg-white rounded shadow-sm">
                                        <span class="text-muted d-block">Total Code</span>
                                        <span class="fw-bold fs-5"><?= $totalCode ?></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-6">
                                    <div class="p-2 bg-white rounded shadow-sm">
                                        <span class="text-muted d-block">CN</span>
                                        <span class="fw-bold fs-5"><?= $totalExtraCode ?></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-6">
                                    <div class="p-2 bg-white rounded shadow-sm">
                                        <span class="text-muted d-block">Total Amount</span>
                                        <span class="fw-bold fs-5">₹<?= number_format($totalAmount, 2) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-6">
                                    <div class="p-2 bg-white rounded shadow-sm">
                                        <span class="text-muted d-block">Total Paid</span>
                                        <span class="fw-bold fs-5 text-success">₹<?= number_format($totalPaid, 2) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-2 col-6">
                                    <div class="p-2 bg-white rounded shadow-sm">
                                        <span class="text-muted d-block">Remaining</span>
                                        <span class="fw-bold fs-5 text-danger">₹<?= number_format($totalRemaining, 2) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-3 text-center text-muted">No transactions found for this customer.</div>
                    <?php endif; ?>
                </div>


            </div>
        </div>
        <div class="section mt-3 mb-4">
            <h4 class="mb-3">Payment History</h4>

            <div class="row">
                <?php if (!empty($paymentHistroy)): ?>
                    <?php foreach ($paymentHistroy as $pay): ?>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card shadow-sm border-0" style="border-radius:10px;">
                                <div class="card-body p-3"> <!-- padding added -->
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="card-title mb-3">Amount: ₹<?= esc($pay['amount']) ?></h6>
                                        <form method="post" action="<?= base_url('admin/payment-history/delete/' . $pay['id']) ?>" style="display:inline;">
                                            <button type="submit" class="btn-icon delete" onclick="return confirm('Are you sure?')">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </form>
                                    </div>
                                     <p class="mb-0">
                                        <strong> TransactionId:</strong>
                                        <?= esc($pay['transaction_id']) ?>
                                    </p>
                                    <p class="mb-0">
                                        <strong> Before Paid:</strong>
                                        <?= esc($pay['before_paid_amount']) ?>
                                    </p>
                                    <p class="mb-0">
                                        <strong> After Paid:</strong>
                                        <?= esc($pay['after_paid_amount']) ?>
                                    </p>
                                     <p class="mb-0">
                                        <strong> Payment Method:</strong>
                                        <?= esc($pay['payment_method']) ?>
                                    </p>
                                    <p class="mb-2">
                                        <strong>Date:</strong>
                                        <?= date('d M Y, h:i A', strtotime($pay['created_at'])) ?>
                                    </p>
                                    
                                   <hr>
                                    <p>
                                        Remark: <?= esc($pay['remark']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No payment history found.</p>
                <?php endif; ?>
            </div>

        </div>

    </div>
    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>