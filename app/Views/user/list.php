<?php echo view('header'); ?>

<body>
  <!-- Full Page Loader -->
  <?php echo view('topHeader'); ?>
  <div id="appCapsule" class="full-height">
    <div class="user-container">
      <div class="user-list">
        <!-- Header Section -->
        <div class="user-list-header">
          <h5>User List</h5>
          <div class="right-section">
            <div class="add">
              <a href="#" class="button" data-bs-toggle="modal" data-bs-target="#addUserModel">
                <ion-icon name="add-outline"></ion-icon>
                <span>Add User</span>
              </a>
            </div>
          </div>
        </div>

        <!-- User Table -->
        <div class="table-responsive">
         <table id="example" class="table table-modern">
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
            <tbody>
              <?php if (!empty($users)): ?>
                <?php
                $start = 1 + ($pager->getCurrentPage() - 1) * $pager->getPerPage();
                foreach ($users as $index => $user):
                ?>
                  <tr>
                    <td><?= $start + $index ?></td>
                    <td><?= esc($user['name']) ?></td>
                    <td><?= esc($user['email']) ?></td>
                    <td><?= esc($user['phone']) ?></td>
                    <td><?= esc($user['address']) ?></td>
                    <td> <img src="<?= base_url('assets/uploads/logos/' . $user['profile_image']) ?>" alt="Logo" width="50">></td>
                    <td class="text-center">
                      <div class="d-flex justify-content-center gap-2">

                        <!-- Edit Button -->
                        <a href="<?= base_url('admin/user/edit/' . $user['id']) ?>"
                          class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center action-btn"
                          title="Edit User">
                          <ion-icon name="create-outline"></ion-icon>
                        </a>

                        <!-- Delete Button -->
                        <form method="post" action="<?= base_url('admin/user/delete/' . $user['id']) ?>">
                          <button type="submit"
                            class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center action-btn"
                            onclick="return confirm('Are you sure?')"
                            title="Delete User">
                            <ion-icon name="trash-outline"></ion-icon>
                          </button>
                        </form>

                      </div>
                    </td>

                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php echo view('user/add-user-modal'); ?>

  <?php echo view('sidebar'); ?>
  <?php echo view('bottomMenu'); ?>
  <?php echo view('footerlink'); ?>