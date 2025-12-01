<?php echo view('header'); ?>

<body>
  <!-- Full Page Loader -->
  <?php echo view('topHeader'); ?>
  <div id="appCapsule" class="full-height">
    <div class="user-container">
      <div class="user-list">
        <!-- Header Section -->
        <div class="user-list-header premium-header">
          <h5><ion-icon name="people-outline"></ion-icon> User List</h5>

          <div class="header-actions">
            <a href="#" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addUserModel">
              <ion-icon name="add-circle-outline"></ion-icon> Add User
            </a>
          </div>
        </div>


        <!-- User Table -->
        <div class="table-responsive">

          <table id="userTable" class="table table-modern">
            <thead>
              <tr>
                <th>Sr.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>Address</th>
                <th>Avatar</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="userBody"></tbody>
          </table>



        </div>
      </div>
    </div>
  </div>
  <?php echo view('user/add-user-modal'); ?>
  <script>
    window.appConfig = {
      listDataUrl: "<?= base_url('admin/listData') ?>",
      logoUrl: "<?= base_url('assets/uploads/logos/') ?>",
      editUrl: "<?= base_url('admin/user/edit/') ?>"
    };
  </script>

  <?php echo view('sidebar'); ?>
  <?php echo view('bottomMenu'); ?>
  <?php echo view('footerlink'); ?>