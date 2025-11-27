<?php echo view('header'); ?>

<body>
    <!-- App Header -->
    <?php echo view('topHeader');?>
    <!-- * App Header -->


    <!-- App Capsule -->
    <div id="appCapsule">

        <!-- Product Header Card -->

        <div class="section pt-2">
            <div
                style="
            width:100%;
            background:#fff;
            border-radius:18px;
            padding:18px 20px;
            box-shadow:0 4px 14px rgba(0,0,0,0.10);
        ">
                <div style="
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
        ">
                    <!-- LEFT SIDE: PRODUCT INFO -->
                    <div style="flex:1;">

                        <div style="font-size:13px; opacity:0.7; margin-bottom:4px;">
                            PRODUCT INFORMATION
                        </div>

                        <h2 style="margin:0; font-size:22px; font-weight:700;">
                            <?= esc($product['company_name']) ?>
                        </h2>

                        <div style="margin-top:10px; font-size:14px; line-height:1.6;">
                            <div><strong>Owner:</strong> <?= esc($product['name']) ?></div>
                            <div><strong>Email:</strong> <?= esc($product['email']) ?></div>
                            <div>
                                <strong>Website:</strong>
                                <a href="<?= esc($product['url']) ?>"
                                    target="_blank"
                                    style="color:#0d6efd; text-decoration:none;">
                                    <?= esc($product['url']) ?>
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT SIDE: PRODUCT LOGO -->
                    <div style="flex-shrink:0;">
                        <img src="<?= base_url('assets/uploads/logos/' . $product['logo']) ?>"
                            style="
                        width:110px;
                        height:110px;
                        border-radius:16px;
                        object-fit:cover;
                        box-shadow:0 4px 14px rgba(0,0,0,0.18);
                     ">
                    </div>

                </div>

            </div>
        </div>

        <!-- * Product Header Card -->



        <!-- PRODUCT STATS -->
        <div class="section">

            <div class="section-heading mt-2 mb-2">
                <h2 class="title">Overview</h2>
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <a href="<?= base_url('admin/customer-list?client_id=' . $product['id']) ?>">
                        <div class="stat-box border rounded p-3 shadow-sm">
                            <div class="title fw-bold text-secondary">Total Business Value</div>
                            <div class="value text-primary fs-5"><?= esc($totalAmount) ?></div>
                        </div>
                    </a>
                </div>

                <div class="col-6">
                    <a href="<?= base_url('admin/customer-list?client_id=' . $product['id']) ?>">

                        <div class="stat-box border rounded p-3 shadow-sm">
                            <div class="title fw-bold text-secondary">Total Clients</div>
                            <div class="value text-primary fs-5"><?= esc(count($customers)) ?></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row mt-2">

                <div class="col-6">
                    <a href="<?= base_url('admin/transaction-list?client_id=' .$product['id']) ?>">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="title fw-bold text-secondary">Total Transactions</div>
                        <div class="value text-success fs-5"><?= esc(count($transactions)) ?></div>
                    </div>
                    </a>
                </div>
                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="title fw-bold text-secondary">Total License Keys</div>
                        <div class="value text-success fs-5"><?= esc($totalCode + $totalextraCode) ?></div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">

                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="title fw-bold text-secondary">License keys</div>
                        <div class="value text-primary fs-5"><?= esc($totalCode) ?></div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="title fw-bold text-secondary">Free Keys(CN)</div>
                        <div class="value text-success fs-5"><?= esc($totalextraCode) ?></div>
                    </div>
                </div>

            </div>
            <div class="row mt-2">

                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="title fw-bold text-secondary">Paid Amount</div>
                        <div class="value text-success fs-5">₹ <?= esc($totalPaidAmount) ?></div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="title fw-bold text-secondary">Pending Amount</div>
                        <div class="value text-danger fs-5">₹ <?= esc($totalPaindingAmount) ?></div>
                    </div>
                </div>

            </div>

            <div class="row mt-2">

                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="title fw-bold text-secondary">This Month</div>
                        <div class="value text-primary fs-5"></div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stat-box border rounded p-3 shadow-sm">
                        <div class="title fw-bold text-secondary">Today</div>
                        <div class="value text-success fs-5"></div>
                    </div>
                </div>

            </div>

        </div>
        <!-- * PRODUCT STATS -->



        <!-- CHARTS -->
        <div class="section mt-3">
            <div class="section-heading mb-2">
                <h2 class="title">Insights</h2>
            </div>

            <div class="row">

                <div class="col-4 text-center">
                    <h6 class="fw-bold text-secondary">Month</h6>
                    <canvas id="monthChart" width="100"></canvas>
                </div>

                <div class="col-4 text-center">
                    <h6 class="fw-bold text-secondary">Today</h6>
                    <canvas id="todayChart" width="100"></canvas>
                </div>

                <div class="col-4 text-center">
                    <h6 class="fw-bold text-secondary">Paid/Pending</h6>
                    <canvas id="paidPendingChart" width="100"></canvas>
                </div>

            </div>
        </div>
        <!-- * CHARTS -->



        <!-- TRANSACTIONS -->
        <div class="section mt-4">
            <div class="section-heading">
                <h2 class="title">Recent Transactions</h2>
            </div>

            <div class="transactions">

                <?php if (!empty($productTransactions)): ?>
                    <?php foreach ($productTransactions as $t): ?>

                        <a href="<?= base_url('product/' . $product['id'] . '/transaction/' . $t['id']) ?>" class="item">
                            <div class="detail">
                                <strong>#<?= $t['id'] ?> — <?= $t['code'] ?></strong>
                                <p>Client: <?= $t['client_name'] ?></p>
                            </div>
                            <div class="right">
                                <div class="price text-success">₹</div>
                            </div>
                        </a>

                    <?php endforeach; ?>
                <?php else: ?>

                    <div class="text-center mt-4">
                        <h5 class="text-muted">No transactions found</h5>
                    </div>

                <?php endif; ?>

            </div>
        </div>
        <!-- * TRANSACTIONS -->

    </div>

    <!-- * App Capsule -->


    <!-- App Bottom Menu -->
    <?php echo view('bottomMenu'); ?>
    <!-- * App Bottom Menu -->

    <!-- App Sidebar -->
    <?php echo view('sidebar'); ?>
    <!-- * App Sidebar -->



    <?php echo view('footerlink'); ?>