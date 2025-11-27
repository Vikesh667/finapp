<?php echo view('header'); ?>

<!-- BEGIN appCapsule -->

<body>
    <?php echo view('topHeader');?>
    <div id="appCapsule">
        <div class="container mt-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4>Login History</h4>

                <div class="alert alert-info p-2 mb-0">
                    <strong>Last Login:</strong>
                    <?= isset($lastLogin['login_time']) ? date("d M Y, h:i A", strtotime($lastLogin['login_time'])) : 'No Record'; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>User Login Activity</strong>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Login Time</th>
                                    <th>Logout Time</th>
                                    <th>Duration</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php if (!empty($history)): ?>
                                    <?php foreach ($history as $index => $row): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= date("d M Y, h:i A", strtotime($row['login_time'])) ?></td>
                                            <td>
                                                <?= $row['logout_time']
                                                    ? date("d M Y, h:i A", strtotime($row['logout_time']))
                                                    : '<span class="text-danger">Active / Not Logged Out</span>'; ?>
                                            </td>
                                             <td>
                                                <?php
                                                if ($row['logout_time']) {
                                                    $start = new DateTime($row['login_time']);
                                                    $end   = new DateTime($row['logout_time']);
                                                    $diff  = $start->diff($end);

                                                    echo $diff->h . 'h ' . $diff->i . 'm ' . $diff->s . 's';
                                                } else {
                                                    echo '<span class="text-danger">Active</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?= $row['ip_address'] ?? '-' ?></td>

                                            <td style="max-width: 250px; word-break: break-word;">
                                                <?= $row['user_agent'] ?? '-' ?>,
                                                <?= $row['platform'] ?>
                                            </td>
                                             <td>
                                                <?= $row['location'] ?>
                                             </td>
                                            <td>
                                                <?php if ($row['status'] == 'success'): ?>
                                                    <span class="badge bg-success">Success</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Failed</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">No Login Records Found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div> <!-- container -->

    </div>
    <!-- END appCapsule -->

    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>