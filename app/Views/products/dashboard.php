<?php echo view('header'); ?>

<body>
    <!-- App Header -->
    <?php echo view('topHeader'); ?>
    <!-- * App Header -->


    <!-- App Capsule -->
    <div id="appCapsule">

        <!-- Product Header Card -->

        <div class="product-card premium-card mt-3">
            <div class="row g-0 align-items-center">

                <!-- LEFT SIDE -->
                <div class="col-md-8 p-4 d-flex flex-column justify-content-center">
                    <div class="badge-title">
                        <ion-icon name="information-circle-outline"></ion-icon> Product Information
                    </div>

                    <h2 class="product-title"><?= esc($product['company_name']) ?></h2>

                    <div class="product-info-list mt-2">

                        <p>
                            <ion-icon name="person-circle-outline"></ion-icon>
                            <strong>Owner:</strong> <?= esc($product['name']) ?>
                        </p>

                        <p>
                            <ion-icon name="mail-outline"></ion-icon>
                            <strong>Email:</strong> <?= esc($product['email']) ?>
                        </p>

                        <?php if (!empty($product['url'])): ?>
                            <p>
                                <ion-icon name="link-outline"></ion-icon>
                                <strong>Website:</strong>
                                <a href="<?= esc($product['url']) ?>" target="_blank"><?= esc($product['url']) ?></a>
                            </p>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- RIGHT SIDE: LOGO -->
                <div class="col-md-4 p-3 d-flex justify-content-center align-items-center">
                    <div class="logo-box">
                        <img src="<?= base_url('assets/uploads/logos/' . $product['logo']) ?>" alt="logo">
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
                    <a href="<?= base_url('admin/transaction-list?client_id=' . $product['id']) ?>">
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