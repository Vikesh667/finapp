<?php echo view('header'); ?>

<body>
  <!-- Full Page Loader -->


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
          <table id='example' class="table table-striped table-bordered">
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
                      <!-- Edit Button -->
                      <a href="<?= base_url('admin/user/edit/' . $user['id']) ?>" class="btn-icon edit" title="Edit User">
                        <ion-icon name="create-outline"></ion-icon>
                      </a>


                      <!-- Delete Button -->
                      <form method="post" action="<?= base_url('admin/user/delete/' . $user['id']) ?>" style="display:inline;">
                        <button type="submit" class="btn-icon delete" onclick="return confirm('Are you sure?')">
                          <ion-icon name="trash-outline"></ion-icon>
                        </button>
                      </form>

                    </td>

                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center text-muted py-3">No users found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <?php if ($pager && $pager->getPageCount('users') > 1): ?>
            <nav aria-label="Custom pagination" class="mt-3">
              <ul class="pagination justify-content-center">

                <!-- Previous Button -->
                <?php if ($pager->getCurrentPage('users') > 1): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= $pager->getPreviousPageURI('users') ?>">Previous</a>
                  </li>
                <?php else: ?>
                  <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                  </li>
                <?php endif; ?>


                <!-- Page Numbers -->
                <?php for ($page = 1; $page <= $pager->getPageCount('users'); $page++): ?>
                  <?php if ($page == $pager->getCurrentPage('users')): ?>
                    <li class="page-item active">
                      <span class="page-link"><?= $page ?></span>
                    </li>
                  <?php else: ?>
                    <li class="page-item">
                      <a class="page-link" href="<?= $pager->getPageURI($page, 'users') ?>"><?= $page ?></a>
                    </li>
                  <?php endif; ?>
                <?php endfor; ?>


                <!-- Next Button -->
                <?php if ($pager->getCurrentPage('users') < $pager->getPageCount('users')): ?>
                  <li class="page-item">
                    <a class="page-link" href="<?= $pager->getNextPageURI('users') ?>">Next</a>
                  </li>
                <?php else: ?>
                  <li class="page-item disabled">
                    <span class="page-link">Next</span>
                  </li>
                <?php endif; ?>

              </ul>
            </nav>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

  <div class="modal fade action-sheet" id="addUserModel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <!-- Header -->
        <div class="modal-header">
          <h5 class="modal-title">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body">
          <div class="action-sheet-content">
            <?php $errors = session()->getFlashdata('error'); ?>
            <?php if (session()->getFlashdata('success')): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('admin/user/add') ?>" id="addUserForm">
              <?= csrf_field() ?>

              <!-- Name -->
              <div class="form-group basic mb-3">
                <label class="label">Full Name</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="name" value="<?= old('name') ?>" placeholder="Enter full name" required>
                </div>
                <?php if (isset($errors['name'])): ?>
                  <span class="text-danger"><?= esc($errors['name']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Email -->
              <div class="form-group basic mb-3">
                <label class="label">Email Address</label>
                <div class="input-group">
                  <input type="email" class="form-control" name="email" value="<?= old('email') ?>" placeholder="Enter email address" required>
                </div>
                <?php if (isset($errors['email'])): ?>
                  <span class="text-danger"><?= esc($errors['email']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Phone -->
              <div class="form-group basic mb-3">
                <label class="label">Mobile Number</label>
                <div class="input-group">
                  <input type="text" class="form-control" name="phone" value="<?= old('phone') ?>" placeholder="Enter mobile number" required>
                </div>
                <?php if (isset($errors['phone'])): ?>
                  <span class="text-danger"><?= esc($errors['phone']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Address -->
              <div class="form-group basic mb-3">
                <label class="label">Address</label>
                <div class="input-group">
                  <textarea class="form-control" name="address" placeholder="House no., street, etc." rows="2" required><?= old('address') ?></textarea>
                </div>
                <?php if (isset($errors['address'])): ?>
                  <span class="text-danger"><?= esc($errors['address']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Country -->

              <div class="form-group basic mb-3">
                <label class="label">Country</label>
                <div class="input-group">
                  <select name="country" id="countrySelect" class="form-control" required>
                    <option value="">Select Country</option>
                  </select>

                </div>
                <?php if (isset($errors['country'])): ?>
                  <span class="text-danger"><?= esc($errors['country']) ?></span>
                <?php endif; ?>
              </div>

              <!-- State -->
              <div class="form-group basic mb-3">
                <label class="label">State</label>
                <div class="input-group">
                  <select name="state" id="stateSelect" class="form-control" required>
                    <option value="">Select State</option>
                  </select>
                </div>
                <?php if (isset($errors['state'])): ?>
                  <span class="text-danger"><?= esc($errors['state']) ?></span>
                <?php endif; ?>
              </div>

              <!-- City -->
              <div class="form-group basic mb-3">
                <label class="label">City</label>
                <div class="input-group">
                  <select name="city" id="citySelect" class="form-control" required>
                    <option value="">Select City</option>
                  </select>
                </div>
                <?php if (isset($errors['city'])): ?>
                  <span class="text-danger"><?= esc($errors['city']) ?></span>
                <?php endif; ?>
              </div> 
              <!-- Password -->
              <div class="form-group basic mb-3">
                <label class="label">Password</label>
                <div class="input-group">
                  <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                </div>
                <?php if (isset($errors['password'])): ?>
                  <span class="text-danger"><?= esc($errors['password']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Buttons -->
              <div class="form-group basic mt-4">
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                  <ion-icon name="person-add-outline"></ion-icon> Add User
                </button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php echo view('sidebar'); ?>
  <?php echo view('bottomMenu'); ?>
  <?php echo view('footerlink'); ?>