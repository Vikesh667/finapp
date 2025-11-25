<?php echo view('header'); ?>

<body>
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="logo" class="logo">
        </div>
        <div class="right">
            <a href="<?= base_url('app-notifications') ?>" class="headerButton">
                <ion-icon class="icon" name="notifications-outline"></ion-icon>
                <span class="badge badge-danger">4</span>
            </a>
            <a href="<?= base_url('app-settings') ?>" class="headerButton">
                <img src="<?= base_url('assets/img/sample/avatar/avatar1.jpg') ?>" alt="image" class="imaged w32">
                <span class="badge badge-danger">6</span>
            </a>
        </div>
    </div>
    <div class="edit-client-section mt-5 mb-5">
        <div class="edit-client-wrapper">
            <!-- Header -->
            <div class="edit-client-header mb-3">
                <h4>Edit Product</h4>
                <?php
                $role = session()->get('role'); // Get current user's role
                $backUrl = ($role === 'admin') ? base_url('admin/client-list') : base_url('user/client-list');
                ?>
                <a href="<?= $backUrl ?>" class="btn-back">← Back</a>

            </div>

            <!-- Form Card -->
            <div class="edit-client-card">
                <form method="post" action="<?= base_url('admin/client/update') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= esc($client['id']) ?>">
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" name="username" value="<?= esc($client['username']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= esc($client['name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" name="company_name" value="<?= esc($client['company_name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?= esc($client['email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Website URL</label>
                        <input type="text" name="url" value="<?= esc($client['url']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Company Logo</label>
                        <div class="logo-preview">
                            <img src="<?= base_url('assets/uploads/logos/' . $client['logo']) ?>"
                                alt="<?= esc($client['name']) ?>">
                        </div>
                        <input type="file" name="logo" accept="image/*">
                        <small class="hint-text">Leave blank if you don't want to change the logo.</small>
                    </div>

                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Update</button>
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <?php echo view('sidebar'); ?>

    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>