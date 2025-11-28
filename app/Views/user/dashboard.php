<?php echo view('header'); ?>

<body>
    <?php echo view('topHeader'); ?>

    <div id="appCapsule">

        <?php
        // 🔹 Dummy values (for safe UI testing)
        $overall_amount      = 120000;
        $amount_with_gst     = 85000;
        $amount_without_gst  = 35000;
        $totalClient         = 8;
        $totalCustomer       = 54;
        $totalTransactions   = 135;
        $totalCode           = 980;
        $extraCode           = 35;
        $totalPaid           = 105000;
        $totalRemaining      = 15000;
        $thisMonthRevenue    = 25000;
        $todayRevenue        = 4000;

        $transactions = [
            ['id' => 1001, 'customer_id' => 1, 'code' => 'FLB-5672', 'paid_amount' => 3500],
            ['id' => 1002, 'customer_id' => 2, 'code' => 'FLB-4123', 'paid_amount' => 2000],
            ['id' => 1003, 'customer_id' => 3, 'code' => 'FLB-9944', 'paid_amount' => 6000],
        ];
        ?>

        <!-- 🌟 Revenue Card -->
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card glass-card">
                <div class="row g-0 align-items-center">

                    <!-- LEFT -->
                    <div class="col-md-7 p-2 d-flex align-items-center gap-3">
                        <div class="icon-circle-lg">
                            <ion-icon name="trending-up-outline"></ion-icon>
                        </div>
                        <div>
                            <h6 class="label">Your Business Value</h6>
                            <h1 class="amount">₹ <?= number_format($overall_amount) ?></h1>
                            <p class="sub fade-small">Based on your client transactions</p>
                        </div>
                    </div>

                    <!-- RIGHT -->
                    <div class="col-md-5 p-3 border-start summary-side">
                        <div class="summary-item mb-2">
                            <ion-icon name="receipt-outline"></ion-icon>
                            <strong>With GST:</strong>
                            <span>₹ <?= number_format($amount_with_gst) ?></span>
                        </div>
                        <div class="summary-item">
                            <ion-icon name="pricetag-outline"></ion-icon>
                            <strong>Without GST:</strong>
                            <span>₹ <?= number_format($amount_without_gst) ?></span>
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
                        <span class="stat-title">My Products</span>
                        <h3 class="stat-value"><?= $totalClient ?></h3>
                        <ion-icon name="layers-outline" class="stat-icon"></ion-icon>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-box premium-box">
                        <span class="stat-title">My Clients</span>
                        <h3 class="stat-value"><?= $totalCustomer ?></h3>
                        <ion-icon name="people-outline" class="stat-icon"></ion-icon>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box premium-box">
                        <span class="stat-title">Transactions</span>
                        <h3 class="stat-value"><?= $totalTransactions ?></h3>
                        <ion-icon name="swap-horizontal-outline" class="stat-icon"></ion-icon>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-box premium-box">
                        <span class="stat-title">Total License Keys</span>
                        <h3 class="stat-value"><?= $totalCode ?></h3>
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
                <h2 class="title">Recent Transactions</h2>
                <a href="#" class="link">View All</a>
            </div>

            <div class="transactions">
                <?php foreach ($transactions as $t): ?>
                    <a href="#" class="item premium-transaction">
                        <div class="detail">
                            <strong>#<?= $t['id'] ?></strong>
                            <p>Key: <?= $t['code'] ?></p>
                        </div>
                        <div class="right">
                            <span class="price">₹ <?= $t['paid_amount'] ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
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