<?php echo view('header'); ?>
<?php
$role = session()->get('role');
?>

<body>
    <?php echo view('topHeader'); ?>
    <div id="appCapsule" class="full-height">
        <div class="user-container">
            <div class="user-list">
                <div class="user-list-header premium-header">
                    <h5><ion-icon name="cube-outline"></ion-icon> Product List</h5>

                    <!-- Filter Product by User -->
                    <select name="user_id" id="userSelect" required class="client-select">
                        <option value="">Filter Product By User</option>
                        <?php foreach ($user as $u): ?>
                            <?php if ($u['role'] === 'user'): ?>
                                <option value="<?= esc($u['id']) ?>"><?= esc($u['name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                    <!-- Right Side -->
                    <div class="header-actions">
                        <?php if ($role === 'admin'): ?>
                            <a href="#" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                <ion-icon name="add-circle-outline"></ion-icon> Add Product
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="example" class="table table-modern">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <?php if (session()->get('role') === 'admin'): ?>
                                 <th>CreatedBy</th>
                                <?php endif; ?>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company Name</th>
                                <th>Url</th>
                                <th>Logo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clients)): ?>
                                <?php
                                $start = 1 + ($pager->getCurrentPage() - 1) * $pager->getPerPage();
                                foreach ($clients as $index => $client):
                                ?>
                                    <tr>
                                        <td><?= $start + $index ?></td>
                                        <?php if (session()->get('role') === 'admin'): ?>
                                            <td><?= esc($client['created_by_name']) ?></td>
                                        <?php endif; ?>

                                        <td><?= esc($client['name']) ?></td>
                                        <td><?= esc($client['email']) ?></td>
                                        <td><?= esc($client['company_name']) ?></td>

                                        <td><a href="<?= esc($client['url']) ?>" target="_blank"><?= esc($client['url']) ?></a></td>
                                        <td>
                                            <img src="<?= base_url('assets/uploads/logos/' . $client['logo']) ?>" alt="Logo" width="50">
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center flex-wrap gap-2">

                                                <?php $role = session()->get('role');
                                                $editUrl = ($role === 'admin') ? base_url('admin/client/edit-client/' . $client['id']) : base_url('user/client/edit-client/' . $client['id']);
                                                $deleteUrl = ($role === 'admin') ? base_url('admin/client/delete/' . $client['id']) : base_url('user/client/delete/' . $client['id']); ?>
                                                <!-- Edit -->
                                                <a href="<?= $editUrl ?>"
                                                    class="btn btn-sm btn-outline-primary rounded-circle action-btn"
                                                    title="Edit Client">
                                                    <ion-icon name="create-outline"></ion-icon>
                                                </a>

                                                <!-- Delete -->
                                                <form method="post" action="<?= $deleteUrl ?>" onsubmit="return confirm('Are you sure?')">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger rounded-circle action-btn"
                                                        title="Delete Client">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                </form>

                                                <!-- Assign User -->
                                                <button class="btn btn-sm btn-outline-secondary rounded-pill d-flex align-items-center gap-1 px-3"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#userModal"
                                                    data-client-id="<?= esc($client['id']) ?>"
                                                    title="Assign User">
                                                    <i class="bi bi-person-plus"></i> Assign
                                                </button>

                                                <!-- View -->
                                                <a href="<?= base_url('admin/product/' . $client['id']) ?>"
                                                    class="btn btn-sm btn-warning rounded-pill px-3"
                                                    title="View Client Products">
                                                    View
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <?php echo view('client/add-product'); ?>
    <?php echo view('client/client-assign-modal'); ?>





    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>