<?php echo view('header'); ?>

<body>
    <!-- App Header -->
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
            $profileImage = $session->get('profile_image'); // optional field from DB
            $firstLetter = strtoupper(substr($userName, 0, 1));
            $this->common = model('CommanModel');
            $month = $this->common->revenueChart('commanModel');
            ?>

            <?php if ($role === 'admin'): ?>
                <!-- Admin: Notifications + Avatar -->
                <a href="<?= base_url('admin/app-notifications') ?>" class="headerButton">
                    <ion-icon class="icon" name="notifications-outline"></ion-icon>
                    <span class="badge badge-danger">4</span>
                </a>

                <a href="<?= base_url('admin/app-settings') ?>" class="headerButton">
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
    <!-- * App Header -->


    <!-- App Capsule -->
    <div id="appCapsule">
        <!-- Wallet Card -->
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card">

                <div class="balance" style="display:flex; justify-content:space-between; align-items:flex-start; width:100%;">

                    <!-- LEFT SIDE : OVERALL TOTAL -->
                    <div class="left">
                        <span class="title">Total Business Value</span>
                        <h1 class="total">
                            ₹ <?= esc(number_format($totals['overall_amount'], 2)) ?>
                        </h1>
                        <p class="small text-muted">Including all client transactions</p>
                    </div>

                    <!-- RIGHT SIDE : WITH GST / WITHOUT GST -->
                    <div class="right" style="text-align:right; font-size:15px;">
                        <p class="mb-1">
                            <strong>With GST:</strong><br>
                            ₹ <?= esc(number_format($totals['amount_with_gst'], 2)) ?>
                        </p>
                        <p class="mb-0">
                            <strong>Without GST:</strong><br>
                            ₹ <?= esc(number_format($totals['amount_without_gst'], 2)) ?>
                        </p>
                    </div>

                </div>

            </div>
        </div>


        <!-- Stats -->
        <!-- Ionicons -->
        <div class="section">

            <!-- Row 1 -->
            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box  border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Total Users</div>
                                <div class="value text-success fs-5"><?= esc($totalUser) ?></div>
                            </div>
                            <ion-icon name="people-outline" class="fs-3 text-success"></ion-icon>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <a href="<?= base_url('admin/client-list') ?>">
                        <div class="stat-box  border rounded p-3 shadow-sm">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="title fw-bold text-secondary">Total Product</div>
                                    <div class="value text-danger fs-5"><?= esc($totalClient) ?></div>
                                </div>
                                <ion-icon name="person-outline" class="fs-3 text-danger"></ion-icon>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row mt-2">
                <div class="col-6">

                    <div class="stat-box  border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Total Client</div>
                                <div class="value text-primary fs-5"><?= esc($totalCustomer) ?></div>
                            </div>
                            <ion-icon name="people-circle-outline" class="fs-3 text-primary"></ion-icon>
                        </div>
                    </div>

                </div>

                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Total Transactions</div>
                                <div class="value text-info fs-5"><?= esc($totalTransactions) ?></div>
                            </div>
                            <ion-icon name="swap-horizontal-outline" class="fs-3 text-info"></ion-icon>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box  border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Total License Key</div>
                                <div class="value text-success fs-5"><?= esc($totalCode) ?></div>
                            </div>
                            <ion-icon name="code-slash-outline" class="fs-3 text-success"></ion-icon>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stat-box  border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Free License Key(CN)</div>
                                <div class="value text-warning fs-5"><?= esc($extraCode) ?></div>
                            </div>
                            <ion-icon name="add-circle-outline" class="fs-3 text-warning"></ion-icon>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box  border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Total Paid</div>
                                <div class="value text-success fs-5">₹<?= esc($totalPaid) ?></div>
                            </div>
                            <ion-icon name="cash-outline" class="fs-3 text-success"></ion-icon>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stat-box  border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Pending Payments</div>
                                <div class="value text-danger fs-5">₹<?= esc($totalRemaining) ?></div>
                            </div>
                            <ion-icon name="hourglass-outline" class="fs-3 text-danger"></ion-icon>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 5 -->
            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box  border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Average Transaction</div>
                                <div class="value text-primary fs-5">₹<?= esc($averageTransaction) ?></div>
                            </div>
                            <ion-icon name="stats-chart-outline" class="fs-3 text-primary"></ion-icon>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stat-box  border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Highest Transaction</div>
                                <div class="value text-success fs-5">₹<?= esc($highestTransaction) ?></div>
                            </div>
                            <ion-icon name="trending-up-outline" class="fs-3 text-success"></ion-icon>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row (This Month + Today) -->
            <!-- Row (This Month + Today) -->
            <div class="row mt-2">

                <!-- This Month Revenue -->
                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">This Month</div>
                                <div class="value text-primary fs-5 count-up" data-value="<?= $thisMonthRevenue ?>">0</div>
                            </div>
                            <!-- Better Icon -->
                            <ion-icon name="trending-up-outline" class="fs-3 text-primary"></ion-icon>
                        </div>
                    </div>
                </div>

                <!-- Today Revenue -->
                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Today</div>
                                <div class="value text-success fs-5 count-up" data-value="<?= $todayRevenue ?>">0</div>
                            </div>
                            <!-- Better Icon -->
                            <ion-icon name="cash-outline" class="fs-3 text-success"></ion-icon>
                        </div>
                    </div>
                </div>

            </div>


            <!-- Row 6 -->
            <div class="row mt-2">
                <div class="col-12">
                    <div class="stat-box  border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Last Transaction Code</div>
                                <div class="value text-info fs-5"><?= esc($lastTransactionCode) ?></div>
                            </div>
                            <ion-icon name="receipt-outline" class="fs-3 text-info"></ion-icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">

                <!-- This Month Pie -->
                <div class="col-md-4 col-12 mb-3">
                    <div class="card shadow-sm border-0 p-3 text-center">
                        <h6 class="fw-bold text-secondary mb-2">This Month</h6>
                        <div style="width: 150px; height: 150px; margin: 0 auto;">
                            <canvas id="pieChartMonth"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Today Pie -->
                <div class="col-md-4 col-12 mb-3">
                    <div class="card shadow-sm border-0 p-3 text-center">
                        <h6 class="fw-bold text-secondary mb-2">Today</h6>
                        <div style="width: 150px; height: 150px; margin: 0 auto;">
                            <canvas id="pieChartToday"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Paid vs Pending Pie -->
                <div class="col-md-4 col-12 mb-3">
                    <div class="card shadow-sm border-0 p-3 text-center">
                        <h6 class="fw-bold text-secondary mb-2">Paid vs Pending</h6>
                        <div style="width: 150px; height: 150px; margin: 0 auto;">
                            <canvas id="piePaidPending"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-3">

                <!-- Month Comparison -->
                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Month Comparison</div>
                                <div class="value fs-5">₹<?= number_format($thisMonth) ?></div>
                                <small class="<?= ($monthChange >= 0) ? 'text-success' : 'text-danger' ?>">
                                    <?= ($monthChange >= 0 ? '↑' : '↓') ?>
                                    <?= number_format($monthChange, 1) ?>%
                                    vs Last Month
                                </small>
                            </div>

                            <ion-icon name="<?= ($monthChange >= 0) ? 'trending-up-outline' : 'trending-down-outline' ?>"
                                class="fs-3 <?= ($monthChange >= 0) ? 'text-success' : 'text-danger' ?>">
                            </ion-icon>
                        </div>
                    </div>
                </div>

                <!-- Day Comparison -->
                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="title fw-bold text-secondary">Today Comparison</div>
                                <div class="value fs-5">₹<?= number_format($today) ?></div>
                                <small class="<?= ($dayChange >= 0) ? 'text-success' : 'text-danger' ?>">
                                    <?= ($dayChange >= 0 ? '↑' : '↓') ?>
                                    <?= number_format($dayChange, 1) ?>%
                                    vs Yesterday
                                </small>
                            </div>

                            <ion-icon name="<?= ($dayChange >= 0) ? 'arrow-up-outline' : 'arrow-down-outline' ?>"
                                class="fs-3 <?= ($dayChange >= 0) ? 'text-success' : 'text-danger' ?>">
                            </ion-icon>
                        </div>
                    </div>
                </div>

            </div>
        </div>






        <!-- * Stats -->

        <!-- Transactions -->
        <div class="section mt-4 mb-4">
            <div class="section-heading">
                <h2 class="title">Transactions</h2>
                <a href="<?= base_url('admin/transaction-list') ?>" class="link">View All</a>
            </div>
            <div class="transactions">
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <a href="<?= base_url('admin/customer/customer-detail/' . $transaction['customer_id']) ?>" class="item">
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
        <!-- * Transactions -->


        <!-- app footer -->
        <div class="appFooter">
            <div class="footer-title">
                Copyright © Finapp 2021. All Rights Reserved.
            </div>
            Bootstrap 5 based mobile template.
        </div>
        <!-- * app footer -->

    </div>
    <!-- * App Capsule -->


    <!-- App Bottom Menu -->
    <?php echo view('bottomMenu'); ?>
    <!-- * App Bottom Menu -->

    <!-- App Sidebar -->
    <?php echo view('sidebar'); ?>
    <!-- * App Sidebar -->


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


    <script>
        //  PIE CHART – THIS MONTH
        new Chart(document.getElementById('pieChartMonth'), {
            type: 'pie',
            data: {
                labels: ['Revenue'],
                datasets: [{
                    data: [<?= $thisMonthRevenue ?>],
                    backgroundColor: ['#007bff'],
                    borderWidth: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });


        // ✅ PIE CHART – TODAY
        new Chart(document.getElementById('pieChartToday'), {
            type: 'pie',
            data: {
                labels: ['Revenue'],
                datasets: [{
                    data: [<?= $todayRevenue ?>],
                    backgroundColor: ['#28a745'],
                    borderWidth: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });


        // ✅ PIE CHART – PAID VS PENDING (WITH % INSIDE + ON HOVER)
        new Chart(document.getElementById('piePaidPending'), {
            type: 'pie',
            data: {
                labels: ['Paid', 'Pending'],
                datasets: [{
                    data: [<?= $totalPaid ?>, <?= $totalRemaining ?>],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },

                    // ✅ PERCENT INSIDE SLICES
                    datalabels: {
                        color: "#fff",
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: (value, ctx) => {
                            let total = ctx.chart._metasets[0].total;
                            return ((value / total) * 100).toFixed(1) + "%";
                        }
                    },

                    // ✅ PERCENT ON TOOLTIP
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = ((value / total) * 100).toFixed(1);
                                return `${context.label}: ${pct}% (₹${value.toLocaleString()})`;
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });


        // ✅ COUNTER ANIMATION (WORKS FOR ALL .count-up)
        const counters = document.querySelectorAll(".count-up");

        counters.forEach(counter => {
            const target = parseFloat(counter.getAttribute("data-value"));
            let current = 0;
            const duration = 1000;
            const stepTime = 10;
            const increment = target / (duration / stepTime);

            const update = () => {
                current += increment;
                if (current >= target) {
                    counter.innerText = "₹" + Number(target).toLocaleString();
                } else {
                    counter.innerText = "₹" + Math.floor(current).toLocaleString();
                    setTimeout(update, stepTime);
                }
            };
            update();
        });
    </script>


    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('transactionChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Weekly Transactions',
                    data: [1200, 1500, 800, 1800, 2200, 2700, 2000],
                    borderWidth: 2,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40,167,69,0.2)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
    <?php echo view('footerlink'); ?>