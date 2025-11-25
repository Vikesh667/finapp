<?php echo view('header'); ?>

<body>

    <!-- loader -->
    <!-- <div id="loader">
        <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
    </div> -->
    <!-- * loader -->

    <!-- App Header -->
    <div class="appHeader">
        <div class="left">
            <a href="<?= base_url('/') ?>" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            Settings
        </div>
        <div class="right">
            <a href="<?= 'app-notifications' ?>" class="headerButton">
                <ion-icon class="icon" name="notifications-outline"></ion-icon>
                <span class="badge badge-danger">4</span>
            </a>
        </div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule">

        <?php
        $session = session();
        $userName = $session->get('user_name');
        $profileImage = $session->get('profile_image');
        $firstLetter = strtoupper(substr($userName, 0, 1));
        $role = $session->get('role');
        $address = $session->get('address');
        $city   = $session->get('city');
        $state  = $session->get('state');
        $country = $session->get('country');
        ?>
        <div class="section mt-3 text-center">
            <div class="avatar-section position-relative d-inline-flex flex-column align-items-center">

                <!-- Profile Image or Fallback -->
                <div id="profileContainer"
                    onclick="toggleProfileActions()"
                    class="rounded-circle shadow d-flex align-items-center justify-content-center"
                    style="cursor:pointer; width:110px; height:110px; overflow:hidden; border:3px solid #ddd;">

                    <?php if (!empty($profileImage) && file_exists(FCPATH . 'assets/uploads/logos/' . $profileImage)): ?>
                        <img
                            src="<?= base_url('assets/uploads/logos/' . $profileImage) ?>"
                            alt="avatar"
                            class="w-100 h-100"
                            style="object-fit:cover; object-position:center;">
                    <?php else: ?>
                        <div class="fallback-avatar bg-primary text-white fw-bold d-flex align-items-center justify-content-center"
                            style="width:100%; height:100%; font-size:36px;">
                            <?= esc($firstLetter) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Upload / Remove Buttons -->
                <div id="profileActions" class="mt-2 d-flex gap-2 justify-content-center flex-wrap" style="display:none;">
                    <!-- Upload -->
                    <form id="uploadForm" action="<?= base_url('upload-profile') ?>" method="post" enctype="multipart/form-data" class="d-inline">
                        <input type="file" name="profile_image" id="profileImageInput" accept="image/*" style="display:none"
                            onchange="document.getElementById('uploadForm').submit();">
                        <button type="button" class="btn btn-sm btn-primary px-3" onclick="document.getElementById('profileImageInput').click();">
                            Upload
                        </button>
                    </form>

                    <!-- Remove -->
                    <?php if (!empty($profileImage) && file_exists(FCPATH . 'assets/uploads/logos/' . $profileImage)): ?>
                        <form action="<?= base_url('admin/remove-profile') ?>" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                Remove
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- User Info -->
            <h5 class="mt-4 text-capitalize mb-1">Role: <?= esc($role) ?></h5>
            <h5 class="text-capitalize">Name: <?= esc($userName) ?></h5>
        </div>

        <!-- JS -->
        <script>
            function toggleProfileActions() {
                const actions = document.getElementById('profileActions');
                actions.style.display = (actions.style.display === 'none' || actions.style.display === '') ? 'flex' : 'none';
            }
        </script>


    </div>
    </div>


    <div class="listview-title mt-1">Theme</div>
    <ul class="listview image-listview text inset no-line">
        <li>
            <div class="item">
                <div class="in">
                    <div>
                        Dark Mode
                    </div>
                    <div class="form-check form-switch  ms-2">
                        <input class="form-check-input dark-mode-switch" type="checkbox" id="darkmodeSwitch">
                        <label class="form-check-label" for="darkmodeSwitch"></label>
                    </div>
                </div>
            </div>
        </li>
    </ul>

    <div class="listview-title mt-1">Notifications</div>
    <ul class="listview image-listview text inset">
        <li>
            <div class="item">
                <div class="in">
                    <div>
                        Payment Alert
                        <div class="text-muted">
                            Send notification when new payment received
                        </div>
                    </div>
                    <div class="form-check form-switch  ms-2">
                        <input class="form-check-input" type="checkbox" id="SwitchCheckDefault1">
                        <label class="form-check-label" for="SwitchCheckDefault1"></label>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <a href="#" class="item">
                <div class="in">
                    <div>Notification Sound</div>
                    <span class="text-primary">Beep</span>
                </div>
            </a>
        </li>
    </ul>

    <div class="listview-title mt-1">Profile Settings</div>
    <ul class="listview image-listview text inset">
        <li>
            <a href="#" class="item">
                <div class="in">
                    <div>E-mail</div>
                </div>
                <div>
                    <span><?= esc(session()->get('user_email')) ?></span>
                </div>
            </a>
        </li>
        <li>
            <a href="#" class="item">
                <div class="in">
                    <div>
                        <p> Address</p>
                        <span><?= esc($address) ?></span>
                    </div>
                    <div>
                        <p>City</p>
                        <span><?= esc($city) ?></span>
                    </div>
                    <div>
                        <p>State</p>
                        <span><?= esc($state) ?></span>
                    </div>
                    <div>
                        <p>Country</p>
                        <span><?= esc($country) ?></span>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="<?= (session()->get('role') === 'admin') ? base_url('admin/profile-update/' . $session->get('user_id')) : base_url('user/profile-update/' . $session->get('user_id')) ?>" class="item" id="#depositActionSheet">
                <div class="in">
                    <div>Profile</div>
                </div>
                <div>
                    Update
                </div>
            </a>
        </li>
    </ul>

    <div class="listview-title mt-1">Security</div>
    <ul class="listview image-listview text mb-2 inset">
        <li>
            <?php
            $actionUrl = (session()->get('role') === 'admin') ? base_url('admin/change-password') : base_url('user/change-password');
            ?>
            <a href="<?= $actionUrl ?>" class="item">
                <div class="in">
                    <div>Update Password</div>
                </div>
            </a>
        </li>
        <li>
            <div class="item">
                <div class="in">
                    <div>
                        2 Step Verification
                    </div>
                    <div class="form-check form-switch ms-2">
                        <input class="form-check-input" type="checkbox" id="SwitchCheckDefault3" checked />
                        <label class="form-check-label" for="SwitchCheckDefault3"></label>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <a href="#" class="item">
                <div class="in">
                    <div>Log out all devices</div>
                </div>
            </a>
        </li>
    </ul>


    </div>
    <!-- * App Capsule -->
    <!-- App Bottom Menu -->
    <?php echo view('bottomMenu'); ?>
    <!-- * App Bottom Menu -->

    <?php echo view('footerlink'); ?>