<?php echo view('header'); ?>

<body>
    <?php echo view('topHeader'); ?>

    <div id="appCapsule">

        <?php
        // 🔹 Dummy values (for safe UI testing)
        $thisMonthRevenue    = 25000;
        $todayRevenue        = 4000;
        ?>
        <!-- 🌟 Revenue Card -->
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card glass-card d-flex justify-content-between">
                <div class="row g-0 align-items-center">

                    <!-- LEFT : LOGO -->
                    <div class="col-md-4 p-3 d-flex justify-content-center">
                        <img src="<?= base_url('assets/uploads/logos/' . ($clients[0]['logo'] ?? 'default.png')) ?>"
                            alt="Product Logo"
                            style="width:100px; height:100px; border-radius:16px; object-fit:cover; box-shadow:0 4px 12px rgba(0,0,0,0.25);">
                    </div>

                    <!-- RIGHT : PRODUCT INFO -->
                    <div class="col-md-8 p-3 border-start">

                        <!-- Product Name -->
                        <h4 class="fw-bold mb-2">
                            <ion-icon name="cube-outline"></ion-icon>
                            <?= esc($clients[0]['company_name'] ?? 'Product Name') ?>
                        </h4>

                        <!-- Owned By -->
                        <p class="text-muted mb-1" style="font-size:14px;">
                            <ion-icon name="person-circle-outline"></ion-icon>
                            Owned By:
                            <strong><?= esc($clients[0]['name'] ?? 'Owner Name') ?></strong>
                        </p>

                        <!-- Website URL -->
                        <p class="text-muted mb-1" style="font-size:14px;">
                            <ion-icon name="globe-outline"></ion-icon>
                            <a href="<?= esc($clients[0]['url'] ?? '#') ?>" target="_blank" class="text-primary fw-semibold" style="text-decoration:none;">
                                <?= esc($clients[0]['url'] ?? '') ?>
                            </a>
                        </p>

                        <!-- Created Date -->
                        <p class="text-muted mb-0" style="font-size:14px;">
                            <ion-icon name="time-outline"></ion-icon>
                            Created On:
                            <?= isset($clients[0]['created_at']) ? date('d M, Y', strtotime($clients[0]['created_at'])) : 'N/A' ?>
                        </p>

                    </div>

                </div>

                <div class="row g-0 align-items-center col-md-6">
                    <!-- LEFT -->
                    <div class="col-md-6 p-2 d-flex align-items-center gap-3">
                        <div class="icon-circle-lg">
                            <ion-icon name="trending-up-outline"></ion-icon>
                        </div>
                        <div>
                            <h6 class="label">Your Business Value</h6>
                            <h1 class="amount">₹ <?= number_format($totals['overall_amount']) ?></h1>
                            <p class="sub fade-small">Based on your client transactions</p>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="col-md-5 p-3 border-start summary-side">
                        <div class="summary-item mb-2">
                            <ion-icon name="receipt-outline"></ion-icon>
                            <strong>With GST:</strong>
                            <span>₹ <?= number_format($totals['amount_with_gst']) ?></span>
                        </div>
                        <div class="summary-item">
                            <ion-icon name="pricetag-outline"></ion-icon>
                            <strong>Without GST:</strong>
                            <span>₹ <?= number_format($totals['amount_without_gst']) ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- 🌟 Stats -->
        <div class="section">

            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box premium-box">
                        <span class="stat-title">My Clients</span>
                        <h3 class="stat-value"><?= $totalCustomer ?></h3>
                        <ion-icon name="people-outline" class="stat-icon"></ion-icon>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-box premium-box">
                        <span class="stat-title">Transactions</span>
                        <h3 class="stat-value"><?= $totalTransactions ?></h3>
                        <ion-icon name="swap-horizontal-outline" class="stat-icon"></ion-icon>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box premium-box">
                        <span class="stat-title">Total License Keys</span>
                        <h3 class="stat-value"><?= $totalCode ?></h3>
                        <ion-icon name="key-outline" class="stat-icon"></ion-icon>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-box premium-box">
                        <span class="stat-title">Free Keys(CN)</span>
                        <h3 class="stat-value"><?= $extraCode ?></h3>
                        <ion-icon name="key-outline" class="stat-icon"></ion-icon>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box premium-box">
                        <span class="stat-title">Paid</span>
                        <h3 class="stat-value text-success">₹<?= number_format($totalPaid) ?></h3>
                        <ion-icon name="cash-outline" class="stat-icon"></ion-icon>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-box premium-box">
                        <span class="stat-title">Pending</span>
                        <h3 class="stat-value text-danger">₹<?= number_format($totalRemaining) ?></h3>
                        <ion-icon name="hourglass-outline" class="stat-icon"></ion-icon>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="stat-box  border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Last Transaction Code</div>
                                <div class="value text-info fs-5"><?= esc($recentTransactionCode) ?></div>
                            </div>
                            <ion-icon name="receipt-outline" class="fs-3 text-info"></ion-icon>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🌟 Pie Chart -->
        <div class="section mb-3 mt-3">
            <div class="card shadow-sm border-0 p-3 text-center">
                <h6 class="fw-bold text-secondary mb-2">Paid vs Pending</h6>
                <div class="chart-wrapper">
                    <canvas id="userPiePaidPending"></canvas>
                </div>

            </div>
        </div>

        <!-- 🌟 Recent Transactions -->
        <div class="section mt-4 mb-4">
            <div class="section-heading">
                <h2 class="title">Transactions</h2>
                <a href="<?= base_url('user/transaction-list') ?>" class="link">View All</a>
            </div>
            <div class="transactions">
                <?php if (!empty($recentFiveTransaction)): ?>
                    <?php foreach ($recentFiveTransaction as $transaction): ?>
                        <a href="<?= base_url('user/customer/customer-detail/' . $transaction['customer_id']) ?>" class="item">
                            <div class="detail">
                                <div>
                                    <strong>Transaction ID : <?= esc($transaction['id']) ?></strong>
                                    <p>License Key : <?= esc($transaction['code']) ?></p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price text-success">Paid : ₹ <?= esc($transaction['paid_amount']) ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <h5>Transaction Not found</h5>
                <?php endif; ?>
            </div>
        </div>

        <?php echo view('bottomMenu'); ?>
        <?php echo view('sidebar'); ?>
        <?php echo view('footerlink'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('userPiePaidPending'), {
            type: 'pie',
            data: {
                labels: ['Paid', 'Pending'],
                datasets: [{
                    data: [<?= $totalPaid ?>, <?= $totalRemaining ?>],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 0.3
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>