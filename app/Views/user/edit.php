<?php echo view('header'); ?>

<body>
  <?php echo view('topHeader');?>
    <div class="user-container" style="margin-top: 60px;">
        <div class="user-list">
            <div class="user-list-header">
                <h4>Edit User</h4>
                <a href="<?= base_url('admin/user-list') ?>" class="btn btn-secondary btn-sm">← Back</a>
            </div>

            <div class="card p-4 shadow-sm" style="border-radius: 12px;">
                <form method="post" action="<?= base_url('admin/user/update') ?>" id="editUserForm">
                    <input type="hidden" name="id" value="<?= esc($user['id']) ?>">

                    <!-- Name -->
                    <div class="form-group basic mb-3">
                        <label class="label">Full Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="name" value="<?= esc($user['name']) ?>" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group basic mb-3">
                        <label class="label">Email Address</label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" value="<?= esc($user['email']) ?>" required>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="form-group basic mb-3">
                        <label class="label">Mobile Number</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="phone" value="<?= esc($user['phone']) ?>" required>
                        </div>
                    </div>


                    <div class="form-group basic mb-3">
                        <label class="label">Address</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="address" value="<?= esc($user['address']) ?>" required>
                        </div>
                    </div>

                    <!-- Country -->
                    <div class="form-group basic mb-3">
                        <label class="label">Country</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="country" value="<?= esc($user['country']) ?>" required>
                        </div>
                    </div>

                    <!-- State -->
                    <div class="form-group basic mb-3">
                        <label class="label">State</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="state" value="<?= esc($user['state']) ?>" required>
                        </div>
                    </div>

                    <!-- District -->
                    <div class="form-group basic mb-3">
                        <label class="label">City</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="city" value="<?= esc($user['city']) ?>" required>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="form-group basic mt-4 d-flex flex-column flex-md-row gap-3">
                        <button type="submit" class="btn btn-primary btn-lg flex-fill">
                            <ion-icon name="save-outline"></ion-icon> Update User
                        </button>
                        <a href="<?= base_url('user-list') ?>" class="btn btn-outline-secondary btn-lg flex-fill">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php echo view('sidebar'); ?>

    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>