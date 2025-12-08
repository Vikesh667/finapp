<?php
$userName  = session()->get('user_name');
$userEmail = session()->get('user_email');
$profileImage = session()->get('profile_image');
$role      = session()->get('role'); // 'admin' or 'user'
$this->common = model('CommanModel');
$counter = $this->common->getcounter();

?>

<div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body pb-5">
                <!-- profile box -->
                <div class="profileBox pt-2 pb-2">
                    <div class="image-wrapper">
                        <img
                            src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
                            alt="avatar"
                            class="rounded-circle shadow"
                            style="width: 32px; height: 32px; object-fit:cover; object-position:center;">
                    </div>
                    <div class="in">
                        <strong><?= esc($userName); ?></strong>
                        <div class="text-muted"><?= esc($userEmail); ?></div>
                    </div>
                    <a href="#" class="btn btn-link btn-icon sidebar-close" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                    </a>
                </div>
                <!-- * profile box -->

                <!-- balance -->
                <div class="sidebar-balance">
                    <div class="listview-title">Total Business Value</div>
                    <div class="in">
                        <h1 class="amount">₹ <?= esc($counter) ?> </h1>
                    </div>
                </div>
    
                <!-- menu -->
                <div class="listview-title mt-1">Menu</div>
                <ul class="listview flush transparent no-line image-listview">

                    <li>
                        <a href="<?=(session()->get('role')==='admin') ? base_url('/admin') :base_url('user') ?>" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="pie-chart-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Overview
                            </div>
                        </a>
                    </li>

                    <!-- Show only for Admin -->
                    <?php if ($role === 'admin'): ?>
                        <li>
                            <a href="<?= base_url('admin/user-list') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="person-outline"></ion-icon>
                                </div>
                                <div class="in">Manage User</div>
                            </a>
                        </li>

                        <li>
                            <a href="<?= base_url('admin/client-list') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="people-outline"></ion-icon>
                                </div>
                                <div class="in">Manage Product</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/customer-list') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="people-outline"></ion-icon>
                                </div>
                                <div class="in">Manage Client</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/transaction-list') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="sync-outline"></ion-icon>
                                </div>
                                <div class="in">Transaction</div>
                            </a>
                        </li>

                        <li>
                            <a href="<?= base_url('admin/service-list') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="layers-outline"></ion-icon>

                                </div>
                                <div class="in">Category</div>
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- Show only for User -->
                        <li>

                            <a href="<?= base_url('user/customer-list') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="person-outline"></ion-icon>
                                </div>
                                <div class="in"> Client List</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('user/transaction-list') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="sync-outline"></ion-icon>
                                </div>
                                <div class="in">Transaction</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('user/app-settings') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="settings-outline"></ion-icon>
                                </div>
                                <div class="in">Settings</div>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <!-- * menu -->

                <!-- others -->
                <div class="listview-title mt-1">Others</div>
                <ul class="listview flush transparent no-line image-listview">

                    <?php if ($role === 'admin'): ?>
                        <li>
                            <a href="<?= base_url('admin/company-manage') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="business-outline"></ion-icon>
                                </div>
                                <div class="in">Company Info</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('admin/app-settings') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="settings-outline"></ion-icon>
                                </div>
                                <div class="in">Settings</div>
                            </a>
                        </li>

                        <li>
                            <a href="<?= base_url('admin/component-messages') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="chatbubble-outline"></ion-icon>
                                </div>
                                <div class="in">Support</div>
                            </a>
                        </li>
                        <li class="has-submenu">
                            <a href="javascript:void(0)" class="item submenu-toggle">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="time-outline"></ion-icon>
                                </div>
                                <div class="in">History</div>
                                <ion-icon name="chevron-down-outline" class="submenu-arrow"></ion-icon>
                            </a>

                            <ul class="submenu">
                                <li>
                                    <a href="<?= base_url('admin/login-history') ?>" class="item">
                                        <div class="icon-box ">
                                            <ion-icon name="log-in-outline"></ion-icon>
                                        </div>
                                        <div class="in">Login History</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url('admin/client-assign-history') ?>" class="item">
                                        <div class="icon-box ">
                                            <ion-icon name="people-outline"></ion-icon>
                                        </div>
                                        <div class="in">Product Assign History</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= base_url('admin/customer-assign-history') ?>" class="item">
                                        <div class="icon-box">
                                            <ion-icon name="person-add-outline"></ion-icon>
                                        </div>
                                        <div class="in">Client Assign History</div>
                                    </a>
                                </li>
                                 <li>
                                    <a href="<?= base_url('admin/customer-delete-history') ?>" class="item">
                                        <div class="icon-box">
                                            <ion-icon name="person-add-outline"></ion-icon>
                                        </div>
                                        <div class="in">Client Delete History</div>
                                    </a>
                                </li>
                            </ul>

                        </li>


                    <?php else: ?>
                        <li>
                            <a href="<?= base_url('user/support') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="chatbubble-outline"></ion-icon>
                                </div>
                                <div class="in">Support</div>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('user/login-history') ?>" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="time-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Login History
                                </div>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a href="<?= base_url('/logout') ?>" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="log-out-outline"></ion-icon>
                            </div>
                            <div class="in">Log out</div>
                        </a>
                    </li>

                </ul>
                <!-- * others -->
            </div>
        </div>
    </div>
</div>