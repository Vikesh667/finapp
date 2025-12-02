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
                    <table id="userTable" class="table table-modern">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company Name</th>
                                <th>Url</th>
                                <th>Logo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="clientBody"></tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>
    <?php echo view('client/add-product'); ?>
    <?php echo view('client/client-assign-modal'); ?>

<script>
window.appConfig = {
    clientListDataUrl: "<?= base_url('admin/listClientData') ?>",
    logoUrl: "<?= base_url('assets/uploads/logos/') ?>",

    editClientUrl: "<?= base_url('admin/client/edit-client/') ?>",
    deleteClientUrl: "<?= base_url('admin/client/delete/') ?>",

    viewClientProductUrl: "<?= base_url('admin/product/') ?>",

    isAdmin: "<?= session()->get('role') === 'admin' ? 1 : 0 ?>"
};
</script>




    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>