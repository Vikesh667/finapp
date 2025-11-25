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
            <div class="user-list">
                <div class="user-list-header">
                    <h5>Service List</h5>
                    <div class="right-section">
                        <div class="add">
                            <?php if ($role === 'admin'): ?>
                                <a href="#" class="button" data-bs-toggle="modal" data-bs-target="#addService">
                                    <ion-icon name="add-outline"></ion-icon>
                                    <span>Create Services</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id='example' class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($services)): ?>
                                <?php
                                foreach ($services as $index => $service): ?>
                                    <tr>
                                        <td><?= esc($service['name']) ?></td>
                                        <td><?= esc($service['created_at']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $role = session()->get('role');
                                            $editUrl = ($role === 'admin')
                                                ? base_url('admin/client/edit-client/' . $service['id'])
                                                : base_url('user/client/edit-client/' . $service['id']);
                                            $deleteUrl = ($role === 'admin')
                                                ? base_url('admin/service/delete/' . $service['id'])
                                                : base_url('user/service/delete/' . $service['id']);
                                            ?>
                                            <a href="<?= $editUrl ?>" class="btn-icon edit" title="Edit Client">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>


                                            <!-- Delete Button -->
                                            <form method="post" action="<?= $deleteUrl ?>" style="display:inline;">
                                                <button type="submit" class="btn-icon delete" onclick="return confirm('Are you sure?')">
                                                    <ion-icon name="trash-outline"></ion-icon>
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">No services found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade action-sheet" id="addService" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Add New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="action-sheet-content">
                        <form action="<?= base_url('admin/service/add') ?>" method="post" id="addClientForm">
                            <!-- Common Fields -->
                            <div class="form-group basic mb-3">
                                <label class="label">Service Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" placeholder="Enter service name" required>
                                </div>
                            </div>
                            <div class="form-group basic mt-4">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <ion-icon name="business-outline"></ion-icon> Add Service
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>