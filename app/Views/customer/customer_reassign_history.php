<?php echo view('header'); ?>

<body>
    <div class="appHeader bg-primary text-light">

        <?php
        $session = session();
        $userName = $session->get('user_name');
        $profileImage = $session->get('profile_image');
        $firstLetter = strtoupper(substr($userName, 0, 1));
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
                <img
                    src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
                    alt="avatar"
                    class="rounded-circle shadow"
                    style="width:32px; height:32px; object-fit:cover; object-position:center;">
                <span class="badge badge-danger">6</span>
            </a>
        </div>
    </div>
    <div id="appCapsule">
        <div class="section mt-3">
            <h4>Reassign History</h4>
            <p class="text-muted">Track all customer reassignment activities.</p>
        </div>

        <div class="section full">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto; white-space: nowrap;">
                        <table class="table table-striped table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>From User</th>
                                    <th>To User</th>
                                    <th>Reassigned By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($assign_history)): ?>
                                    <?php foreach ($assign_history as $index => $row): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>

                                            <td><?= $row['customer_name'] ?></td>

                                            <td>
                                                <span class="badge bg-danger">
                                                    <?= $row['old_user_name'] ?>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge bg-success">
                                                    <?= $row['new_user_name'] ?>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge bg-primary">
                                                    <?= $row['reassigned_by_name'] ?>
                                                </span>
                                            </td>

                                            <td>
                                                <?= date("d M Y, h:i A", strtotime($row['created_at'])) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            No Reassign History Found
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