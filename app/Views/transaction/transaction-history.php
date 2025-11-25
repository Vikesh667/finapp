<?php echo view('header'); ?>

<body>
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
            ?>

            <?php if ($role === 'admin'): ?>
                <!-- Admin: Notifications + Avatar -->
                <a href="<?= base_url('admin/app-notifications') ?>" class="headerButton">
                    <ion-icon class="icon" name="notifications-outline"></ion-icon>
                    <span class="badge badge-danger">4</span>
                </a>

                <a href="<?= base_url('admin/app-settings') ?>" class="headerButton">
                    <?php if (!empty($profileImage) && file_exists(FCPATH . 'assets/uploads/logos' . $profileImage)): ?>
                        <img
                            src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
                            alt="avatar"
                            class="rounded-circle shadow"
                            style="width:32px; height:32px; object-fit:cover; object-position:center;">
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
    <div id="appCapsule" class="full-height">
        <div class="user-container">
            <div class="user-list mt-5 mb-5">
                <div class="user-list-header">
                    <h5>transaction History</h5>
                    <!-- <div class="right-section">
                        <div class="add">
                            <a href="#" class="button" data-bs-toggle="modal" data-bs-target="#addtransactionModal">
                                <ion-icon name="add-outline"></ion-icon>
                                <span>Edit</span>
                            </a>
                        </div>
                    </div> -->
                </div>
                <div class="table-responsive">
                    <table id='example' class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Customer Name</th>
                                <th> Amount</th>
                                <th> Before Paid Amount</th>
                                <th>after_paid_amount</th>
                                <th>Paid Date</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions)): ?>
                                <?php
                                $start = 1 + ($pager->getCurrentPage() - 1) * $pager->getPerPage();
                                foreach ($transactions as $index => $transaction):
                                ?>
                                    <tr>
                                        <td><?= $start + $index ?></td>
                                        <td><?= esc($transaction['customer_name']) ?></td>
                                        <td><?= esc($transaction['amount']) ?></td>
                                        <td><?= esc($transaction['before_paid_amount']) ?></td>
                                        <td><?= esc($transaction['after_paid_amount']) ?></td>
                                        <td><?=esc($transaction['created_at'])?></td>
                                        <td><?=esc($transaction['remark'])?></td>
                                        <td class="text-center">
                                            <?php
                                            $role = session()->get('role');
                                            $actionUrl = ($role === 'admin')
                                                ? ('admin/transaction/edit/' . $transaction['id'])
                                                : ('user/transaction/edit/' . $transaction['id']);
                                            ?>
                                            <a href="<?=base_url($actionUrl) ?>" class="btn-icon edit" title="Edit User">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>
                                              <a href="<?=base_url('user/transaction-history/' .$transaction['id']) ?>" class="btn-icon edit " title="Transaction History" >
                                                  <ion-icon name="document-text-outline"></ion-icon>
                                            </a>
                                            <form method="post" action="<?= base_url('client/delete/' . $transaction['id']) ?>" style="display:inline;">
                                                <button type="submit" class="btn-icon delete" onclick="return confirm('Are you sure?')">
                                                    <ion-icon name="trash-outline"></ion-icon>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <!-- FIXED: colspan should match total <th> count (11) -->
                                    <td colspan="11" class="text-center text-muted py-3">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <?php echo view('sidebar'); ?>
    <?php echo view('footerlink'); ?>