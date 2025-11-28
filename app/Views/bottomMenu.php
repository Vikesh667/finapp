<div class="appBottomMenu">
    <a href="<?=(session()->get('role')==='admin') ? base_url('/admin') : base_url('/user') ?>" class="item active">
        <div class="col">
            <ion-icon name="pie-chart-outline"></ion-icon>
            <strong>Overview</strong>
        </div>
    </a>
    <a href="<?=(session()->get('role')==='admin') ? base_url('admin/transaction-list') : base_url('user/transaction-list') ?>" class="item">
        <div class="col">

            <ion-icon name="receipt-outline"></ion-icon>

            <strong>All Transactions</strong>
        </div>
    </a>
    <a href="<?=(session()->get('role')==='admin') ? base_url('admin/customer-list') : base_url('user/customer-list') ?>" class="item">
        <div class="col">
            <ion-icon name="people-outline"></ion-icon>
            <strong>Clients</strong>
        </div>
    </a>
    <?php if(session()->get('role')==='admin'):?>
    <a href="<?=(session()->get('role')==='admin') ? base_url('admin/client-list') : base_url('user/client-list') ?>" class="item">
        <div class="col">
            <ion-icon name="bag-handle-outline"></ion-icon>
            <strong>Products</strong>
        </div>
    </a>
    <?php endif;?>
    <a href="<?=(session()->get('role')==='admin') ? base_url('admin/app-settings') :base_url('user/app-settings') ?>" class="item">
        <div class="col">
            <ion-icon name="settings-outline"></ion-icon>
            <strong>Settings</strong>
        </div>
    </a>
</div>