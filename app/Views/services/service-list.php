<?php echo view('header'); ?>
<?php
$role = session()->get('role');
?>

<body>
    <div id="loader">
    <img src="<?= base_url('assets/img/logo.png') ;?>" class="loader-logo">
</div>
    <?php echo view('topHeader'); ?>
    <div id="appCapsule" class="full-height">
        <div class="user-container">
            <div class="user-list">
                <div class="user-list-header premium-header">
                    <h5><ion-icon name="layers-outline"></ion-icon> Service List</h5>

                    <div class="header-actions">
                        <?php if ($role === 'admin'): ?>
                            <a href="#" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addService">
                                <ion-icon name="add-circle-outline"></ion-icon> Create Service
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="example" class="table table-modern">
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