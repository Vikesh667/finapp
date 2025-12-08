<?php $path = service('uri')->getPath(); ?>
<div class="appBottomMenu">

    <!-- Overview -->
    <a href="<?= (session()->get('role')==='admin') ? base_url('admin') : base_url('user') ?>"
       class="item <?= ($path === '/finapp/admin' || $path === '/finapp/user') ? 'active' : '' ?>">
        <div class="col">
            <ion-icon name="pie-chart-outline"></ion-icon>
            <strong>Overview</strong>
        </div>
    </a>

    <!-- All Transactions -->
    <a href="<?= (session()->get('role')==='admin') ? base_url('admin/transaction-list') : base_url('user/transaction-list') ?>"
       class="item <?= ($path === '/finapp/admin/transaction-list' || $path === '/finapp/user/transaction-list') ? 'active' : '' ?>">
        <div class="col">
            <ion-icon name="receipt-outline"></ion-icon>
            <strong>All Transactions</strong>
        </div>
    </a>

    <!-- Clients -->
    <a href="<?= (session()->get('role')==='admin') ? base_url('admin/customer-list') : base_url('user/customer-list') ?>"
       class="item <?= ($path === '/finapp/admin/customer-list' || $path === '/finapp/user/customer-list') ? 'active' : '' ?>">
        <div class="col">
            <ion-icon name="people-outline"></ion-icon>
            <strong>Clients</strong>
        </div>
    </a>

    <!-- Products (Admin only) -->
    <?php if(session()->get('role')==='admin'): ?>
    <a href="<?= base_url('admin/client-list') ?>"
       class="item <?= ($path === '/finapp/admin/client-list') ? 'active' : '' ?>">
        <div class="col">
            <ion-icon name="bag-handle-outline"></ion-icon>
            <strong>Products</strong>
        </div>
    </a>
    <?php endif; ?>

    <!-- Settings -->
    <a href="<?= (session()->get('role')==='admin') ? base_url('admin/app-settings') : base_url('user/app-settings') ?>"
       class="item <?= ($path === '/finapp/admin/app-settings' || $path === '/finapp/user/app-settings') ? 'active' : '' ?>">
        <div class="col">
            <ion-icon name="settings-outline"></ion-icon>
            <strong>Settings</strong>
        </div>
    </a>

</div>
