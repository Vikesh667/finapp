<?php echo view('header'); ?>
<?php
$role = session()->get('role');
?>

<body>
    <?php echo view('topHeader'); ?>
    <div id="appCapsule" class="full-height">
        <div class="user-container">
            <div class="user-list">
                <div class="user-list-header">
                    <h5>Product List</h5>
                    <div class="select-client mb-2">
                        <select name="user_id" id="userSelect" required
                            class="form-select"
                            style="border-radius: 10px;">
                            <option value="">Filter Product By User</option>
                            <?php foreach ($user as $u): ?>
                                <?php if ($u['role'] === 'user'): ?>
                                    <option value="<?= esc($u['id']) ?>"><?= esc($u['name']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>



                    <div class="right-section">
                        <div class="add">
                            <?php if ($role === 'admin'): ?>
                                <a href="#" class="button" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                    <ion-icon name="add-outline"></ion-icon>
                                    <span>Add Product</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id='example' class="table table-striped table-bordered">
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

                                        <td><?= esc($client['url']) ?></td>
                                        <td>
                                            <img src="<?= base_url('assets/uploads/logos/' . $client['logo']) ?>" alt="Logo" width="50">
                                        </td>

                                        <td class="text-center">
                                            <?php
                                            $role = session()->get('role');
                                            $editUrl = ($role === 'admin')
                                                ? base_url('admin/client/edit-client/' . $client['id'])
                                                : base_url('user/client/edit-client/' . $client['id']);
                                            $deleteUrl = ($role === 'admin')
                                                ? base_url('admin/client/delete/' . $client['id'])
                                                : base_url('user/client/delete/' . $client['id']);
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
                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#userModal"
                                                data-client-id="<?= esc($client['id']) ?>">
                                                <i class="bi bi-person-plus"></i> Assign User
                                            </button>

                                            <a href="<?= base_url('admin/product/' . $client['id']) ?>" class="btn bg-info">view</a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">No users found</td>
                                </tr>
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