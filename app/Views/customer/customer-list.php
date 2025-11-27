<?php echo view('header'); ?>

<body>
   <?php echo view('topHeader');?>
    <div id="appCapsule" class="full-height dark-mode">
        <div class="user-container">
            <div class="user-list mt-5 mb-5">
                <div class="user-list-header">
                    <h5>Client List</h5>
                    <div class="select-client">
                        <select name="client_id" id="clientSelect" style="width: 300px; padding: 8px; border-radius: 10px; border: 1px solid #ccc; font-size: 16px;">
                            <option value="">Filter Client by Product</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= esc($client['id']) ?>"><?= esc($client['company_name']) ?>(<?= esc($client['name']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="right-section d-flex align-items-center gap-3">
                        <a href="<?= base_url('admin/customer-list') ?>" class="btn btn-secondary">
                            <ion-icon name="refresh-outline"></ion-icon>
                            Refresh
                        </a>

                        <!-- Add Client Button -->
                        <div class="add">
                            <a href="#"
                                class="btn btn-success d-flex align-items-center gap-1 px-3 py-2"
                                data-bs-toggle="modal" data-bs-target="#addCustomerModal"
                                style="border-radius: 8px;">
                                <ion-icon name="add-outline" style="font-size:18px;"></ion-icon>
                                <span>Add Client</span>
                            </a>
                        </div>

                        <!-- Reassign All Button -->

                        <?php if (session()->get('role') === 'admin'): ?>
                            <button class="btn btn-primary d-flex align-items-center gap-1 px-3 py-2"
                                data-bs-toggle="modal" data-bs-target="#bulkReassignModal"
                                style="border-radius: 8px;">
                                <ion-icon name="swap-horizontal-outline" style="font-size:18px;"></ion-icon>
                                <span>Reassign All</span>
                            </button>
                        <?php endif; ?>
                    </div>


                </div>
                <div class="table-responsive">
                    <table id='example' class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>CreatedBy</th>
                                <th>Product Name</th>
                                <th>Shop Name</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Action</th>
                                <?php if (session()->get('role') === 'admin'): ?>
                                    <th>Assign</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($customers)): ?>
                                <?php
                                $start = 1 + ($pager->getCurrentPage() - 1) * $pager->getPerPage();
                                foreach ($customers as $index => $customer):
                                ?>
                                    <tr>
                                        <td><?= $start + $index ?></td>
                                        <td><?= esc($customer['created_by_name']) ?></td>
                                        <td><?= esc($customer['client_name']) ?></td>
                                        <td><?= esc($customer['shop_name']) ?></td>
                                        <td><?= esc($customer['name']) ?></td>
                                        <td><?= esc($customer['device_type']) ?> </td>
                                        <td class="text-center">
                                            <?php
                                            $role = session()->get('role');
                                            $actionUrl = ($role === 'admin')
                                                ? ('admin/customer/edit/' . $customer['id'])
                                                : ('user/customer/edit/' . $customer['id']);
                                            ?>
                                            <a href="<?= base_url($actionUrl) ?>" class="btn-icon edit" title="Edit User">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>
                                            <?php
                                            $role = session()->get('role');
                                            $actionUrlPath = ($role === 'admin')
                                                ? ('admin/transaction-history/' . $customer['id'])
                                                : ('user/transaction-history/' . $customer['id']);
                                            $actionUrlPathDetails = ($role === 'admin')
                                                ? ('admin/customer/customer-detail/' . $customer['id'])
                                                : ('user/customer/customer-detail/' . $customer['id']);
                                            ?>

                                            <a href="<?= base_url($actionUrlPath) ?>" class="btn-icon edit " title="Transaction History">
                                                <ion-icon name="document-text-outline"></ion-icon>
                                            </a>

                                            <a href="<?= base_url($actionUrlPathDetails) ?>" class="btn-icon  " title="Transaction History">
                                                <ion-icon name="eye-outline"></ion-icon>
                                            </a>
                                            <form method="post" action="<?= base_url('admin/customer/delete/' . $customer['id']) ?>" style="display:inline;">
                                                <button type="submit" class="btn-icon delete" onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-unlock"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <?php if (session()->get('role') === 'admin'): ?>
                                            <td class="text-center">

                                                <!-- ✅ Add this Reassign Button -->

                                                <button
                                                    class="btn btn-sm btn-warning reassign-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reassignCustomerModal"
                                                    data-customer-id="<?= $customer['id'] ?>"
                                                    data-customer-name="<?= esc($customer['name']) ?>"
                                                    data-client-id="<?= $customer['client_id'] ?>">
                                                    <ion-icon name="swap-horizontal-outline"></ion-icon> Reassign
                                                </button>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <!-- FIXED: colspan should match total <th> count (11) -->
                                    <td colspan="11" class="text-center text-muted py-3">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
      <?php echo view('customer/add-customer');?>
      <?php echo view('customer/customer-assign');?>
    <!-- ✅ Reassign Customer Modal -->
   

 

    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>