<?php echo view('header'); ?>

<body>

    <div class="section mt-3 text-center">
        <h3>Change Password</h3>
    </div>

    <div class="section mt-2 p-2">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php elseif (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php
        $actionUrlPath = (session()->get('role') === 'admin') ? base_url('admin/update-password') : base_url('user/update-password');
        ?>
        <form method="post" action="<?= $actionUrlPath ?>">
            <div class="card p-3">
                <div class="form-group basic">
                    <label class="label">Old Password</label>
                    <div class="input-group">
                        <input type="password" name="old_password" class="form-control" placeholder="Enter old password" required>
                    </div>
                </div>

                <div class="form-group basic">
                    <label class="label">New Password</label>
                    <div class="input-group">
                        <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                    </div>
                </div>

                <div class="form-group basic">
                    <label class="label">Confirm New Password</label>
                    <div class="input-group">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter new password" required>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary btn-block">Update Password</button>
                </div>
            </div>
        </form>
    </div>

    <?php echo view('footerlink'); ?>