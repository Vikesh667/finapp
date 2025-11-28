<?php echo view('header'); ?>

<body>
    <?php echo view('topHeader'); ?>
    <div id="appCapsule" class="full-height dark-mode">
        <div class="user-container">
            <div class="user-list mt-5 mb-5">
                <div class="user-list-header premium-header">
                    <h5><ion-icon name="people-circle-outline"></ion-icon> Client List</h5>

                    <select name="client_id" id="clientSelect" class="client-select">
                        <option value="">Filter Client by Product</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= esc($client['id']) ?>"><?= esc($client['company_name']) ?> (<?= esc($client['name']) ?>)</option>
                        <?php endforeach; ?>
                    </select>

                    <div class="header-actions">
                        <a href="<?= base_url('admin/customer-list') ?>" class="btn btn-reset">
                            <ion-icon name="refresh-outline"></ion-icon> Refresh
                        </a>

                        <a href="#" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                            <ion-icon name="add-circle-outline"></ion-icon> Add Client
                        </a>

                        <?php if (session()->get('role') === 'admin'): ?>
                            <button class="btn btn-action" data-bs-toggle="modal" data-bs-target="#bulkReassignModal">
                                <ion-icon name="swap-horizontal-outline"></ion-icon> Reassign All
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="example" class="table table-modern">
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
                                            <div class="d-flex justify-content-center flex-wrap gap-2">
                                                <?php $role = session()->get('role');
                                                $actionUrl = ($role === 'admin') ? ('admin/customer/edit/' . $customer['id']) : ('user/customer/edit/' . $customer['id']);
                                                $actionUrlPath = ($role === 'admin') ? ('admin/transaction-history/' . $customer['id']) : ('user/transaction-history/' . $customer['id']);
                                                $actionUrlPathDetails = ($role === 'admin') ? ('admin/customer/customer-detail/' . $customer['id']) : ('user/customer/customer-detail/' . $customer['id']);
                                                ?>

                                                <!-- Edit -->

                                                <!-- Transaction History -->
                                                <a href="<?= base_url($actionUrlPath) ?>"
                                                    class="btn btn-sm btn-outline-secondary rounded-circle action-btn"
                                                    title="Transaction History">
                                                    <ion-icon name="document-text-outline"></ion-icon>
                                                </a>

                                                <!-- View Details -->
                                                <a href="<?= base_url($actionUrlPathDetails) ?>"
                                                    class="btn btn-sm btn-outline-info rounded-circle action-btn"
                                                    title="Customer Details">
                                                    <ion-icon name="eye-outline"></ion-icon>
                                                </a>

                                                <!-- Delete -->
                                                <form method="post" action="<?= base_url('admin/customer/delete/' . $customer['id']) ?>"
                                                    onsubmit="return confirm('Are you sure?')" style="display:inline;">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger rounded-circle action-btn"
                                                        title="Delete Customer">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                </form>

                                            </div>
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
    <?php echo view('customer/add-customer'); ?>
    <?php echo view('customer/customer-assign'); ?>
    <!-- ✅ Reassign Customer Modal -->




    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>