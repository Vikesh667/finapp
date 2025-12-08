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

                <input
                    type="text"
                    id="searchCustomer"
                    class="form-control mb-3"
                    placeholder="Search customers..." />
                <div class="table-responsive">
                    <table id="customerTable" class="table table-modern"> <!-- ✔ table ID -->
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>CreatedBy</th>
                                <th>Product Name</th>
                                <th>Shop Name</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <!-- AJAX inserts rows here -->
                        <tbody id="customerBody"></tbody> <!-- ✔ only tbody has this ID -->

                    </table>


                </div>
                <div id="customerPagination" class="mt-3"></div>

            </div>
        </div>
    </div>
    <?php echo view('customer/add-customer'); ?>
    <?php echo view('customer/customer-assign'); ?>
    <!-- ✅ Reassign Customer Modal -->
    <script>
        const ROLE = "<?= session()->get('role') ?>"; // admin or user
        const BASE = "<?= base_url() ?>";

        window.appConfig = {
            customerListDataUrl: `${BASE}/${ROLE}/customer/list-data`,
            editCustomerUrl: `${BASE}/${ROLE}/customer/edit/`,
            deleteCustomerUrl: `${BASE}/${ROLE}/customer/delete/`,
            detailViewUrl: `${BASE}/${ROLE}/customer/customer-detail/`,
            transactionHistoryUrl: `${BASE}/${ROLE}/transaction-history/`,
            reassignCustomerUrl: `reassign-customer`, // 🔥 NEW
            isAdmin: "<?= (session()->get('role') === 'admin') ? 1 : 0 ?>"

        };
    </script>




    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>