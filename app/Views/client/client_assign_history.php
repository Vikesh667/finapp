<?php echo view('header'); ?>

<body>
   <?php echo view('topHeader');?>

    <div id="appCapsule">
        <div class="section mt-3">
            <h4>Client Assign & Reassign History</h4>
            <p class="text-muted">View all history of user assignments for this client.</p>
        </div>

        <div class="section full">
            <div class="card">
                <div class="card-body p-0">

                    <div class="table-responsive" style="overflow-x:auto; white-space:nowrap;">
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Admin</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($assign_history)): ?>
                                    <?php foreach ($assign_history as $i => $row): ?>

                                        <tr>
                                            <td><?= $i + 1 ?></td>

                                            <!-- Which user was assigned/unassigned -->
                                            <td><?= esc($row['user_name']) ?></td>

                                            <!-- Action Badge -->
                                            <td>
                                                <?php if ($row['action'] == 'assigned'): ?>
                                                    <span class="badge bg-success">Assigned</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Unassigned</span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Admin who performed action -->
                                            <td>
                                                <span class="badge bg-primary">
                                                    <?= esc($row['admin_name']) ?>
                                                </span>
                                            </td>

                                            <!-- Date & Time -->
                                            <td>
                                                <?= date("d M Y, h:i A", strtotime($row['created_at'])) ?>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            No Assignment History Found
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>
