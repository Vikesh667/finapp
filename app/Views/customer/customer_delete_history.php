<?php echo view('header'); ?>

<body>
    <?php echo view('topHeader'); ?>
    <div id="appCapsule" class="full-height dark-mode">
        <div class="user-container">
            <div class="user-list mt-5 mb-5">
                <div class="user-list-header premium-header">
                    <h5><ion-icon name="people-circle-outline"></ion-icon> Customer Delete History</h5>
                </div>

                <div class="table-responsive">
                    <div class="table-responsive">
                        <table id="customerTable" class="table table-modern"> <!-- ✔ table ID -->
                            <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Product Name</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Shop Name</th>
                                    <th>Deleted At</th>
                                    <th>Deleted By</th>
                                </tr>
                            </thead>

                            <!-- AJAX inserts rows here -->
                            <tbody id="deleteHistoryBody"></tbody> <!-- ✔ only tbody has this ID -->

                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
        window.appConfig = {
           customerDeleteHistoryUrl: "<?= base_url('admin/customer/delete-history') ?>",
        };
    </script>
    <?php echo view('sidebar'); ?>
    <?php echo view('bottomMenu'); ?>
    <?php echo view('footerlink'); ?>