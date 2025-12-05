<?php echo view('header'); ?>

<body>
    <?php echo view('topHeader'); ?>

    <div id="appCapsule" class="dsh-mobile-container">

        <?php
        // --- PHP Data Setup (Used for all sections) ---
        $totalPaid = $totalPaid ?? 0;
        $totalRemaining = $totalRemaining ?? 0;
        $totalCode = $totalCode ?? 0;
        $thisMonthRevenue = $thisMonthRevenue ?? 0;
        $todayRevenue = $todayRevenue ?? 0;
        $rate = $rate ?? 0;
        $totalPaidKeys = 0;
        $totalPendingKeys = 0;
        if (($totalPaid + $totalRemaining) > 0 && $totalCode > 0) {
            $totalPaidKeys = round($totalCode * ($totalPaid / ($totalPaid + $totalRemaining)));
            $totalPendingKeys = $totalCode - $totalPendingKeys; // Corrected calculation: $totalCode - $totalPaidKeys
        }
        // Correcting the pending key calculation again (was using $totalPendingKeys in the line above)
        $totalPendingKeys = $totalCode - $totalPaidKeys;


        $overallAmount = $totals['overall_amount'] ?? 0;
        $amountWithGST = $totals['amount_with_gst'] ?? 0;
        $amountWithoutGST = $totals['amount_without_gst'] ?? 0;
        $totalCustomer = $totalCustomer ?? 0;
        $extraCode = $extraCode ?? 0;
        $recentTransactionCode = $recentTransactionCode ?? 'N/A';
        // --- End PHP Data Setup ---
        ?>

        <div class="header-large-title bg-dark text-light rounded-bottom shadow-sm mb-3 pt-4 pb-2">
            <div class="d-flex align-items-center mb-2 px-3">
                <img src="assets/uploads/logos/<?= esc($clients['0']['logo']) ?>" alt="Finclub" class="me-3 rounded" style="width: 48px; height: 48px; background: #fff; padding: 5px;">
                <div>
                    <h1 class="text-light m-0"><?= esc($clients['0']['company_name']) ?></h1>
                    <h6 class="text-light opacity-75 m-0">Owned by: <?= esc($clients['0']['name']) ?></h6>
                </div>
            </div>
            <small class="d-block mt-1 px-3">
                Website: <a href="<?= esc($clients['0']['url']) ?>" target="_blank" class="text-warning"><?= esc($clients['0']['url']) ?></a>
            </small>
            <h4 class="text-light opacity-75 mt-3 px-3">Welcome to Dashboard! <strong><?= session()->get('user_name') ?></strong></h4>
        </div>
        <div class="section pt-3">
            <div class="row g-3">

                <div class="col-md-7">

                    <div class="card mb-3 p-3">
                        <h4 class="text-muted mb-1 fontsize-sub">Total Business value</h4>
                        <h1 class="text-primary mb-3 fw-bold">
                            ₹ <?= number_format($overallAmount) ?>
                        </h1>
                        <div class="d-flex justify-content-between">
                            <strong class="text-success fontsize-sub">
                                <ion-icon name="checkmark-circle-outline"></ion-icon> With GST: ₹<?= number_format($amountWithGST) ?>
                            </strong>
                            <span class="text-muted fontsize-sub">
                                <ion-icon name="remove-circle-outline"></ion-icon> Without GST: ₹<?= number_format($amountWithoutGST) ?>
                            </span>
                        </div>
                    </div>


                    <h3 class="section-title medium mt-4">Key Metrics Summary</h3>
                    <div class="row mb-3 g-2">
                        <div class="col-6">
                            <div class="card p-2 text-center bg-primary text-light">
                                <h2 class="m-0 fontsize-headingXLarge"><?= $totalCode ?></h2>
                                <h5 class="m-0 fontsize-caption opacity-75">Total Keys</h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card p-2 text-center bg-warning text-dark">
                                <h2 class="m-0 fontsize-headingXLarge"><?= $extraCode ?></h2>
                                <h5 class="m-0 fontsize-caption opacity-75">Free Keys</h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card p-2 text-center bg-success text-light">
                                <h2 class="m-0 fontsize-headingXLarge"><?= number_format($totalLicenseKeys) ?></h2>
                                <h5 class="m-0 fontsize-caption opacity-75">Total Service Keys</h5>
                            </div>
                        </div>

                    </div>
                    <h3 class="section-title medium mt-4">Revenue Trend (Last 6 Months)</h3>
                    <div class="card p-3 mb-3">
                        <div id="revenueTrendChart" style="height: 250px;">
                        </div>
                    </div>
                    <div class="row g-3">

                        <!-- Paid vs Remaining -->
                        <div class="col-12 col-md-4">
                            <div class="card p-3 shadow-sm h-100">
                                <h6 class="fw-bold mb-2 text-center">Paid vs Remaining</h6>
                                <div style="height: 260px;">
                                    <canvas id="paidRemainingChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- GST Breakdown -->
                        <div class="col-12 col-md-4">
                            <div class="card p-3 shadow-sm h-100">
                                <h6 class="fw-bold mb-2 text-center">GST Breakdown</h6>
                                <div style="height: 260px;">
                                    <canvas id="gstChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Today Revenue -->
                        <div class="col-12 col-md-4">
                            <div class="card p-3 shadow-sm text-center h-100">
                                <h6 class="fw-bold">Today Revenue</h6>
                                <div style="height: 210px;">
                                    <canvas id="todayRevenueGauge"></canvas>
                                </div>
                                <h3 class="fw-bold mt-2 text-primary">₹ <?= number_format($todayRevenue) ?></h3>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="col-md-5">

                    <h3 class="section-title medium mt-2">Financial Snapshots</h3>
                    <div class="row mb-3 g-3">

                        <div class="col-6">
                            <div class="dashboard-card bg-gradient-primary">
                                <div class="label">THIS MONTH REVENUE</div>
                                <div class="value" id="thisMonthValue">₹ <?= esc($thisMonthRevenue) ?></div>
                                <small class="sub">Includes GST</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="dashboard-card bg-gradient-primary">
                                <div class="label">TODAY REVENUE</div>
                                <div class="value" id="todayValue">₹ <?= esc($todayRevenue) ?></div>
                                <small class="sub">Includes GST</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="dashboard-card border-warning text-warning">
                                <div class="label">PENDING AMOUNT</div>
                                <div class="value" id="pendingValue">₹ <?= esc($totalRemaining) ?></div>
                                <small class="sub">Total Dues</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="dashboard-card border-success text-success">
                                <div class="label">TOTAL PAID</div>
                                <div class="value" id="paidValue">₹ <?= esc($totalPaid) ?></div>
                                <small class="sub">Collections Till Date</small>
                            </div>
                        </div>

                    </div>

                    <h3 class="section-title medium mt-4">General Statistics</h3>
                    <ul class="listview image-listview flush rounded mb-3">
                        <li>
                            <div class="item">
                                <div class="icon-box bg-secondary text-light">
                                    <ion-icon name="trending-up-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    <div>
                                        <header class="fontsize-sub">TOTAL REVENUE (All Time)</header>
                                        <h4 class="m-0 text-dark">₹ <?= number_format($overallAmount) ?></h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="item">
                                <div class="icon-box bg-info text-light">
                                    <ion-icon name="people-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    <div>
                                        <header class="fontsize-sub">MY CLIENTS</header>
                                        <h4 class="m-0 text-dark"><?= $totalCustomer ?></h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <h3 class="section-title medium mt-4">Last Transaction</h3>
                    <div class="card rounded p-3 mb-3 bg-light">
                        <div class="d-flex align-items-center">
                            <ion-icon name="receipt-outline" class="text-primary me-3" style="font-size: 32px;"></ion-icon>
                            <div class="flex-grow-1">
                                <h6 class="text-muted mb-0 fontsize-sub">Latest Transaction Receipt</h6>
                                <h4 class="text-primary fw-bold m-0"><?= esc($recentTransactionCode) ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center section-title medium mt-4">
                        <h3 class="m-0">Recent Transactions</h3>
                        <a href="<?= base_url('user/transaction-list') ?>" class="btn btn-sm btn-link">View All</a>
                    </div>
                    <ul class="listview link-listview image-listview flush rounded">
                        <?php if (!empty($recentFiveTransaction)): ?>
                            <?php $i = 0;
                            foreach ($recentFiveTransaction as $transaction): ?>
                                <?php if ($i++ >= 2) break; // Limit to 2 items 
                                ?>
                                <li>
                                    <a href="<?= base_url('user/customer/customer-detail/' . esc($transaction['customer_id'])) ?>">
                                        <div class="item">
                                            <div class="icon-box bg-primary text-light">
                                                <ion-icon name="receipt-outline"></ion-icon>
                                            </div>
                                            <div class="in">
                                                <div>
                                                    <header class="fontsize-sub">Transaction ID: <?= esc($transaction['code']) ?></header>
                                                    <div class="text-muted">Customer: <?= esc($transaction['customer_name'] ?? 'Unknown') ?></div>
                                                </div>
                                            </div>
                                            <h4 class="m-0 text-dark">₹ <?= number_format($transaction['paid_amount']) ?></h4>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li>
                                <div class="item text-center text-muted">No recent transactions to display.</div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const chartLabels = <?= $monthlyLabels ?? '[]' ?>;
            const chartData = <?= $monthlyRevenues ?? '[]' ?>;

            const container = document.getElementById('revenueTrendChart');
            if (!container) return;

            if (!chartData || chartData.length === 0) {
                container.innerHTML = '<div class="text-center text-muted p-4">No revenue data to display.</div>';
                return;
            }

            container.innerHTML = '<canvas id="revenueCanvas"></canvas>';
            const ctx = document.getElementById('revenueCanvas').getContext('2d');

            // Gradient background
            const gradient = ctx.createLinearGradient(0, 0, 0, 350);
            gradient.addColorStop(0, 'rgba(98, 54, 255, 0.95)');
            gradient.addColorStop(1, 'rgba(98, 54, 255, 0.25)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Monthly Revenue (₹)',
                        data: chartData,
                        backgroundColor: gradient,
                        borderColor: 'rgba(98, 54, 255, 1)',
                        borderWidth: 1.5,
                        borderRadius: 10,
                        hoverBackgroundColor: 'rgba(98, 54, 255, 1)',
                        hoverBorderColor: '#ffffff',
                        hoverBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 950,
                        easing: 'easeOutQuart',
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#111',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: (ctx) => '₹ ' + ctx.raw.toLocaleString('en-IN')
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    weight: '600'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (v) => '₹' + v.toLocaleString('en-IN'),
                                color: '#6b7280'
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const values = {
                month: <?= $thisMonthRevenue ?>,
                today: <?= $todayRevenue ?>,
                pending: <?= $totalRemaining ?>,
                paid: <?= $totalPaid ?>
            };

            const animate = (id, target) => {
                let n = 0;
                const step = target / 45;
                const loop = () => {
                    n += step;
                    if (n >= target) n = target;
                    document.getElementById(id).innerText = "₹ " + Math.round(n).toLocaleString('en-IN');
                    if (n < target) requestAnimationFrame(loop);
                };
                loop();
            };

            animate("thisMonthValue", values.month);
            animate("todayValue", values.today);
            animate("pendingValue", values.pending);
            animate("paidValue", values.paid);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const paid = <?= $totalPaid ?>;
            const remaining = <?= $totalRemaining ?>;

            const ctx = document.getElementById('paidRemainingChart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Remaining'],
                    datasets: [{
                        data: [paid, remaining],
                        backgroundColor: [
                            'rgba(53, 162, 235, 0.9)', // Paid
                            'rgba(255, 99, 132, 0.9)', // Remaining
                        ],
                        hoverBackgroundColor: [
                            'rgba(53, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%', // makes donut style
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 13
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ctx.label + ": ₹ " + ctx.raw.toLocaleString('en-IN')
                            }
                        }
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const cgst = <?= $totalCgst ?>;
            const sgst = <?= $totalSgst ?>;
            const igst = <?= $totalIgst ?>;

            const ctx = document.getElementById('gstChart').getContext('2d');

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['CGST', 'SGST', 'IGST'],
                    datasets: [{
                        data: [cgst, sgst, igst],
                        backgroundColor: [
                            'rgba(255, 159, 64, 0.9)', // CGST
                            'rgba(75, 192, 192, 0.9)', // SGST
                            'rgba(153, 102, 255, 0.9)' // IGST
                        ],
                        hoverBackgroundColor: [
                            'rgba(255, 159, 64, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 13
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ctx.label + ": ₹ " + ctx.raw.toLocaleString('en-IN')
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const today = <?= $todayRevenue ?>;
            const monthTotal = <?= $thisMonthRevenue ?>;
            const daysPassed = new Date().getDate();
            const avgPerDay = monthTotal / Math.max(daysPassed, 1);

            // If avg is 0, avoid NaN
            let percent = avgPerDay > 0 ? Math.round((today / avgPerDay) * 100) : 0;
            percent = Math.max(0, Math.min(percent, 200)); // cap at 200% max

            const canvas = document.getElementById("todayRevenueGauge");
            if (!canvas) return;
            const ctx = canvas.getContext("2d");

            // Gradient to give 3D-ish feel
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            gradient.addColorStop(0, "#8b5cf6"); // lighter top
            gradient.addColorStop(0.5, "#7c3aed"); // main
            gradient.addColorStop(1, "#4c1d95"); // darker bottom

            // Plugin for soft shadow + center text
            const discPlugin = {
                id: "discShadowCenterText",
                afterDraw(chart, args, opts) {
                    const {
                        ctx,
                        chartArea: {
                            left,
                            right,
                            top,
                            bottom
                        }
                    } = chart;
                    const centerX = (left + right) / 2;
                    const centerY = (top + bottom) / 2;

                    // Shadow around disc
                    ctx.save();
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, (right - left) / 2.1, 0, Math.PI * 2);
                    ctx.shadowColor = "rgba(0,0,0,0.25)";
                    ctx.shadowBlur = 18;
                    ctx.shadowOffsetY = 6;
                    ctx.strokeStyle = "rgba(0,0,0,0)";
                    ctx.stroke();
                    ctx.restore();

                    // Center % text
                    ctx.save();
                    ctx.fillStyle = "#111827";
                    ctx.font = "600 18px system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
                    ctx.textAlign = "center";
                    ctx.textBaseline = "middle";
                    ctx.fillText(percent + "%", centerX, centerY);
                    ctx.restore();
                }
            };

            new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: ["Today vs Avg", "Remaining"],
                    datasets: [{
                        data: [percent, Math.max(0, 100 - Math.min(percent, 100))],
                        backgroundColor: [
                            gradient,
                            "rgba(229, 231, 235, 0.9)" // light grey for rest
                        ],
                        borderWidth: 2,
                        borderColor: "#ffffff",
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: "40%", // smaller cutout = more solid disc
                    rotation: -90 * (Math.PI / 180),
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    if (ctx.dataIndex === 0) {
                                        return "Today vs Avg: " + percent + "%";
                                    }
                                    return "Remaining";
                                }
                            }
                        }
                    }
                },
                plugins: [discPlugin]
            });
        });
    </script>

    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>