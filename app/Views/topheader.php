 <div class="appHeader  text-light bg-primary">

        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="right">
            <?php
            $session = session();
            $role = $session->get('role');
            $userName = $session->get('user_name');
            $profileImage = $session->get('profile_image');
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