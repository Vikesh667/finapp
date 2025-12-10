<?php echo view('header'); ?>

<body>
    <div id="loader">
    <img src="<?= base_url('assets/img/logo.png') ;?>" class="loader-logo">
</div>
    <?php echo view('topHeader'); ?>
    <?php
    $name = $customer['name'] ?? '';
    $initials = '';
    if ($name) {
        $parts = explode(' ', trim($name));
        $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
    }

    // Generate consistent random color based on name
    $colors = ['#7D5FFF', '#FF6B6B', '#4BC0C0', '#FFA726', '#26A69A', '#42A5F5', '#AB47BC'];
    $colorIndex = hexdec(substr(md5($name), 0, 2)) % count($colors);
    $avatarColor = $colors[$colorIndex];

    $avatar = $customer['profile'] ?? '';
    $hasImage = !empty($avatar);
    ?>

    <div id="appCapsule" class="pt-5 pb-4" style="margin-top: 60px;">

        <?php
        // Avatar initials + random color
        $name = $customer['name'] ?? '';
        $initials = '';
        if ($name) {
            $parts = explode(' ', trim($name));
            $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
        }

        $colors = ['#7D5FFF', '#FF6B6B', '#4BC0C0', '#FFA726', '#26A69A', '#42A5F5', '#AB47BC'];
        $colorIndex = hexdec(substr(md5($name), 0, 2)) % count($colors);
        $avatarColor = $colors[$colorIndex];

        $avatar = $customer['profile'] ?? '';
        $hasImage = !empty($avatar);
        ?>

        <div class="section mt-2">
            <div class="card shadow-lg border-0 rounded-4 p-3 customer-wrapper">

                <div class="row g-4 align-items-start">

                    <!-- LEFT PROFILE -->
                    <div class="col-lg-4 col-md-5 col-12 text-center">

                        <!-- Banner -->
                        <div class="customer-banner rounded-4">
                            <?php if ($hasImage): ?>
                                <img src="<?= base_url('assets/uploads/customers/' . $avatar) ?>" class="customer-avatar">
                            <?php else: ?>
                                <div class="avatar-initials" style="background: <?= $avatarColor ?>;">
                                    <?= $initials ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- PROFILE INFO -->
                        <div class="mt-5 profile-content">
                            <h4 class="fw-bold mb-1 mt-5"><?= esc($customer['name']) ?></h4>

                            <?php if ($customer['email']): ?>
                                <p class="customer-contact mb-1">
                                    <ion-icon name="mail-outline"></ion-icon> <?= esc($customer['email']) ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($customer['phone']): ?>
                                <p class="customer-contact mb-1">
                                    <ion-icon name="call-outline"></ion-icon> <?= esc($customer['phone']) ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($createdBy['name'])): ?>
                                <p class="customer-contact mb-2">
                                    <ion-icon name="person-circle-outline"></ion-icon> Added by <?= esc($createdBy['name']) ?>
                                </p>
                            <?php endif; ?>

                            <?php
                            $role = session()->get('role');
                            $editUrl = ($role === 'admin')
                                ? ('admin/customer/edit/' . $customer['id'])
                                : ('user/customer/edit/' . $customer['id']);
                            ?>
                            <a href="<?= base_url($editUrl) ?>" class="btn premium-edit-btn mt-2">
                                <ion-icon name="create-outline"></ion-icon> Edit Profile
                            </a>
                        </div>

                    </div>

                    <!-- RIGHT INFO BOXES -->
                    <div class="col-lg-8 col-md-7 col-12">
                        <div class="row g-3">

                            <?php if (!empty($companyName['company_name'])): ?>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <ion-icon name="cube-outline"></ion-icon>
                                        <div><small>Product Name</small>
                                            <h6><?= esc($companyName['company_name']) ?></h6>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($customer['shop_name']): ?>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <ion-icon name="storefront-outline"></ion-icon>
                                        <div><small>Shop Name</small>
                                            <h6><?= esc($customer['shop_name']) ?></h6>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($customer['device_type']): ?>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <ion-icon name="phone-portrait-outline"></ion-icon>
                                        <div><small>Device Type</small>
                                            <h6><?= esc($customer['device_type']) ?></h6>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($customer['gst_number']): ?>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <ion-icon name="receipt-outline"></ion-icon>
                                        <div><small>GST Number</small>
                                            <h6><?= esc($customer['gst_number']) ?></h6>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <ion-icon name="flag-outline"></ion-icon>
                                    <div><small>Country</small>
                                        <h6><?= esc($customer['country']) ?></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <ion-icon name="map-outline"></ion-icon>
                                    <div><small>State</small>
                                        <h6><?= esc($customer['state']) ?></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <ion-icon name="business-outline"></ion-icon>
                                    <div><small>City</small>
                                        <h6><?= esc($customer['city']) ?></h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="info-box address-box">
                                    <ion-icon name="location-outline"></ion-icon>
                                    <div><small>Address</small>
                                        <h6><?= esc($customer['address'] ?: 'N/A') ?></h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

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
                            <table class="table modern-table mb-0">
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