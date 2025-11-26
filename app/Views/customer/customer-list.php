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
    <div class="modal fade action-sheet" id="addCustomerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add New Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="action-sheet-content">
                        <?php
                        $role = session()->get('role');
                        $userId = session()->get('user_id');

                        $actionUrl = ($role === 'admin')
                            ? base_url('admin/customer/add')
                            : base_url('user/customer/add');
                        ?>

                        <form action="<?= $actionUrl ?>" method="post" id="addCustomerForm">

                            <!-- ADMIN ROLE -->
                            <?php if ($role === 'admin'): ?>
                                <div class="form-group basic mb-3">
                                    <label class="label">Select User</label>
                                    <div class="input-group">
                                        <select name="user_id" id="userSelect_customer" class="form-control" required>
                                            <option value="">Loading users...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group basic mb-3">
                                    <label class="label">Select Client</label>
                                    <div class="input-group">
                                        <select name="client_id" id="clientSelect_customer" class="form-control" required>
                                            <option value="">Select user first</option>
                                        </select>
                                    </div>
                                </div>

                            <?php else: ?>
                                <!--  USER ROLE -->
                                <!-- Hidden input for logged-in user -->
                                <input type="hidden" name="user_id" value="<?= $userId ?>">

                                <div class="form-group basic mb-3">
                                    <label class="label">Select Client</label>
                                    <div class="input-group">
                                        <select name="client_id" id="clientSelect_customer" class="form-control" required>
                                            <option value="">Loading clients...</option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- ✅ COMMON FIELDS -->
                            <div class="form-group basic mb-3">
                                <label class="label">Client Name</label>
                                <div class="input-group">
                                    <input type="text" name="name" class="form-control" placeholder="Enter client name" required>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Email</label>
                                <div class="input-group">
                                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Phone</label>
                                <div class="input-group">
                                    <input type="text" name="phone" class="form-control" placeholder="Enter phone number" required>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Shop Name</label>
                                <div class="input-group">
                                    <input type="text" name="shop_name" class="form-control" placeholder="Enter shop name" required>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Services</label>
                                <div class="input-group">
                                    <select name="device_type" id="" class="form-control  ccc" required>
                                        <option value="">--Select Device--</option>
                                        <?php foreach ($services as $service): ?>
                                            <option><?= esc($service['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">Country</label>
                                <div class="input-group">
                                    <select name="country" id="countrySelect" class="form-control  ccc" required>
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">State</label>
                                <div class="input-group">
                                    <select name="state" id="stateSelect" class="form-control" required>
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">City</label>
                                <div class="input-group">
                                    <select name="city" id="citySelect" class="form-control" required>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group basic mb-3">
                                <label class="label">Address</label>
                                <div class="input-group">
                                    <textarea name="address" class="form-control" placeholder="Enter address" required></textarea>
                                </div>
                            </div>
                            <div class="form-group basic mb-3">
                                <label class="label">GST Number</label>
                                <div class="input-group">
                                    <input name="gst_number" class="form-control"
                                        placeholder="27AAPFU0939F1ZV">

                                </div>
                            </div>
                            <div class="form-group basic mt-4">
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    <ion-icon name="person-add-outline"></ion-icon> Add Client
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ✅ Reassign Customer Modal -->
    <div class="modal fade" id="reassignCustomerModal" tabindex="-1" role="dialog" aria-labelledby="reassignModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?= base_url('admin/reassign-customer') ?>" method="POST" id="reassignForm">
                    <input type="hidden" name="customer_id" id="modalCustomerId">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="reassignModalLabel">Reassign Customer</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" id="modalCustomerName" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select New User (Same Client)</label>
                            <select name="new_user_id" id="modalUserSelect" class="form-control" required>
                                <option value="">-- Select User --</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Reassign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bulk Reassign Modal -->
    <div class="modal fade" id="bulkReassignModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 14px;">

                <!-- Header -->
                <div class="modal-header bg-primary text-white" style="border-radius: 14px 14px 0 0;">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <ion-icon name="swap-horizontal-outline" style="font-size:22px;"></ion-icon>
                        Bulk Reassign Customers
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <form action="<?= base_url('admin/bulk-reassign-customers') ?>" method="post" class="row g-3">

                        <!-- Select Client -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Select Client</label>
                            <select name="client_id" class="form-select" required>
                                <option value="">Select Client</option>
                                <?php foreach ($clients as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['company_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- From User -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">From User</label>
                            <select name="from_user_id" class="form-select" required>
                                <option value="">From User</option>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- To User -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">To User</label>
                            <select name="to_user_id" class="form-select" required>
                                <option value="">To User</option>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="col-12 d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
                                <ion-icon name="swap-horizontal-outline" style="font-size:18px;"></ion-icon>
                                Reassign All Customers
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reassignButtons = document.querySelectorAll('.reassign-btn');
            const userSelect = document.getElementById('modalUserSelect');
            const customerIdInput = document.getElementById('modalCustomerId');
            const customerNameInput = document.getElementById('modalCustomerName');

            reassignButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    const customerId = button.dataset.customerId;
                    const customerName = button.dataset.customerName;
                    const clientId = button.dataset.clientId;

                    // Fill modal data
                    customerIdInput.value = customerId;
                    customerNameInput.value = customerName;
                    userSelect.innerHTML = '<option value="">Loading...</option>';

                    // Fetch users under same client
                    try {
                        const res = await fetch(`<?= base_url('admin/get-client-users/') ?>${clientId}`);
                        const users = await res.json();
                        userSelect.innerHTML = '<option value="">-- Select User --</option>';
                        users.forEach(u => {
                            const option = document.createElement('option');
                            option.value = u.id;
                            option.textContent = u.name;
                            userSelect.appendChild(option);
                        });
                    } catch (error) {
                        console.error('Error loading users:', error);
                        userSelect.innerHTML = '<option value="">Error loading users</option>';
                    }
                });
            });
        });
    </script>

    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>