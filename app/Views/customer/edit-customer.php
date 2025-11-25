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
            <div class="edit-client-header mb-3">
                <h4>Edit Client</h4>
                <a href="<?= base_url('admin/customer-list') ?>" class="btn btn-secondary btn-sm">← Back</a>
            </div>

            <div class="card p-4 shadow-sm" style="border-radius: 12px;">
                <?php
                $role = session()->get('role');
                // Corrected route for client (user)
                $actionUrl = ($role === 'admin') ? base_url('admin/customer/update') : base_url('user/customer/update');
                ?>
                <form action="<?= $actionUrl ?>" method="post">
                    <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                    <input type="hidden" name="user_id" value="<?= $customer['user_id'] ?>">
                    <input type="hidden" name="client_id" value="<?= $customer['client_id'] ?>">
                    <!-- Customer Name -->
                    <div class="form-group basic mb-3">
                        <label class="label">Client Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="name" value="<?= esc($customer['name']); ?>" placeholder="Enter customer name" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group basic mb-3">
                        <label class="label">Email Address</label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" value="<?= esc($customer['email']); ?>" placeholder="Enter email" required>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="form-group basic mb-3">
                        <label class="label">Phone Number</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="phone" value="<?= esc($customer['phone']); ?>" placeholder="Enter phone number" required>
                        </div>
                    </div>

                    <!-- Shop Name -->
                    <div class="form-group basic mb-3">
                        <label class="label">Shop Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="shop_name" value="<?= esc($customer['shop_name']); ?>" placeholder="Enter shop name" required>
                        </div>
                    </div>
                    <div class="form-group basic mb-3">
                        <label class="label">Device Type</label>
                        <?php $device_type = $customer['device_type']; ?>
                        <div class="input-group">
                            <select name="device_type" id="" class="form-control  ccc" required>
                                <option value="">--Select Device--</option>
                                <option value="android" <?php if ($device_type == 'android') {
                                                            echo "selected";
                                                        } ?>>Android</option>
                                <option value="iOS" <?php if ($device_type == 'iOS') {
                                                        echo "selected";
                                                    } ?>>iOS</option>
                                <option value="macOS" <?php if ($device_type == 'macOS') {
                                                            echo "selected";
                                                        } ?>>macOS</option>
                                <option value="tv" <?php if ($device_type == 'tv') {
                                                        echo "selected";
                                                    } ?>>TV</option>
                            </select>
                        </div>
                    </div>
                    <!-- City -->
                    <div class="form-group basic mb-3">
                        <label class="label">City</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="city" value="<?= esc($customer['city']); ?>" placeholder="Enter city" required>
                        </div>
                    </div>

                    <!-- State -->
                    <div class="form-group basic mb-3">
                        <label class="label">State</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="state" value="<?= esc($customer['state']); ?>" placeholder="Enter state" required>
                        </div>
                    </div>

                    <!-- Country -->
                    <div class="form-group basic mb-3">
                        <label class="label">Country</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="country" value="<?= esc($customer['country']); ?>" placeholder="Enter country" required>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-group basic mb-3">
                        <label class="label">Address</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="address" value="<?= esc($customer['address']); ?>" placeholder="Enter country" required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group basic mt-4">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <ion-icon name="person-add-outline"></ion-icon> Update Client
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <?php echo view('sidebar'); ?>

    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>