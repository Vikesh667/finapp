<?php echo view('header'); ?>

<body>
    <div class="appHeader bg-primary text-light">
        <?php
        $session = session();
        $userName = $session->get('user_name');
        $profileImage = $session->get('profile_image');
        ?>
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <img src="assets/img/logo.png" alt="logo" class="logo">
        </div>
        <div class="right">
            <a href="<?= base_url('admin/app-notifications') ?>" class="headerButton">
                <ion-icon class="icon" name="notifications-outline"></ion-icon>
                <span class="badge badge-danger">4</span>
            </a>
            <a href="<?= base_url('admin/app-settings') ?>" class="headerButton">
                <img src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
                     alt="avatar"
                     class="rounded-circle shadow"
                     style="width:32px; height:32px; object-fit:cover; object-position:center;">
            </a>
        </div>
    </div>

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
