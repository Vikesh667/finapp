<?php echo view('header'); ?>

<body>
  <div class="appHeader bg-primary text-light">

    <?php
    $session = session();
    $userName = $session->get('user_name');
    $profileImage = $session->get('profile_image');
    $firstLetter = strtoupper(substr($userName, 0, 1));
    ?>
    <div class="left">
      <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
        <ion-icon name="menu-outline"></ion-icon>
      </a>
    </div>
    <div class="pageTitle">
      <img src="assets/img/logo.png" alt="logo" class="logo">
    </div>
    <div class="right">
      <a href="<?= base_url('admin/app-notifications') ?>" class="headerButton">
        <ion-icon class="icon" name="notifications-outline"></ion-icon>
        <span class="badge badge-danger">4</span>
      </a>
      <a href="<?= base_url('admin/app-settings') ?>" class="headerButton">
        <img
          src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
          alt="avatar"
          class="rounded-circle shadow"
          style="width:32px; height:32px; object-fit:cover; object-position:center;">
        <span class="badge badge-danger">6</span>
      </a>
    </div>
  </div>

  <div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 rounded-4 p-4 mx-auto" style="max-width: 450px;">
      <h4 class="text-center mb-4 fw-bold text-primary">Update Profile</h4>
      <form id="profileForm" enctype="multipart/form-data" action="<?=(session()->get('role')==='admin') ? base_url('admin/profile-save'):base_url('user/profile-save') ?>" method="post">
        <input type="hidden" name="id" value="<?= esc($user['id']) ?>">
        <!-- Profile Image Upload -->
        <div class="profile-images text-center mb-4">
           <input type="hidden" name="old_image" value="<?= esc($profileImage) ?>">
          <label for="fileInput" class="position-relative d-inline-block" style="cursor:pointer;">
            <img
              id="myImage"
              src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
              alt="Profile Image"
              class="rounded-circle border border-3 border-light shadow"
              style="width:120px; height:120px; object-fit:cover; transition:0.3s;">
            <div class="upload-overlay position-absolute top-50 start-50 translate-middle bg-dark bg-opacity-50 text-white rounded-circle d-flex align-items-center justify-content-center" style="width:120px; height:120px; opacity:0; transition:0.3s;">
              <i class="bi bi-camera fs-4"></i>
            </div>
          </label>
          <input type="file" id="fileInput" name="profile_image" accept="image/*" style="display:none;">

        </div>

        <hr>

        <!-- Profile Fields -->
        <div class="form-group basic mb-3">
          <label class="label">Full Name</label>
          <div class="input-group">
            <input type="text" class="form-control" name="name" value="<?= esc($user['name']) ?>" required>
          </div>
        </div>

        <!-- Email -->
        <div class="form-group basic mb-3">
          <label class="label">Email Address</label>
          <div class="input-group">
            <input type="email" class="form-control" name="email" value="<?= esc($user['email']) ?>" required>
          </div>
        </div>

        <!-- Phone -->
        <div class="form-group basic mb-3">
          <label class="label">Mobile Number</label>
          <div class="input-group">
            <input type="text" class="form-control" name="phone" value="<?= esc($user['phone']) ?>" required>
          </div>
        </div>


        <div class="form-group basic mb-3">
          <label class="label">Address</label>
          <div class="input-group">
            <input type="text" class="form-control" name="address" value="<?= esc($user['address']) ?>" required>
          </div>
        </div>

        <!-- Country -->
        <div class="form-group basic mb-3">
          <label class="label">Country</label>
          <div class="input-group">
            <input type="text" class="form-control" name="country" value="<?= esc($user['country']) ?>" required>
          </div>
        </div>

        <!-- State -->
        <div class="form-group basic mb-3">
          <label class="label">State</label>
          <div class="input-group">
            <input type="text" class="form-control" name="state" value="<?= esc($user['state']) ?>" required>
          </div>
        </div>

        <!-- District -->
        <div class="form-group basic mb-3">
          <label class="label">City</label>
          <div class="input-group">
            <input type="text" class="form-control" name="city" value="<?= esc($user['city']) ?>" required>
          </div>
        </div>
         <div class="form-group basic mt-4 d-flex flex-column flex-md-row gap-3">
                        <button type="submit" class="btn btn-primary btn-lg flex-fill">
                            <ion-icon name="save-outline"></ion-icon> Update User
                        </button>
                        <a href="<?= base_url('user/app-settings') ?>" class="btn btn-outline-secondary btn-lg flex-fill">
                            Cancel
                        </a>
                    </div>
      </form>
    </div>
  </div>


  <script>
    // JavaScript to trigger the click
    document.getElementById('myImage').addEventListener('click', function() {
      document.getElementById('fileInput').click();
    });
  </script>












  <?php echo view('sidebar'); ?>
  <?php echo view('bottomMenu'); ?>
  <?php echo view('footerlink'); ?>