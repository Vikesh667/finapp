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
             <div class="dropdown">
                 <a href="#" class="headerButton" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                     <ion-icon class="icon" name="notifications-outline"></ion-icon>
                     <span id="notifyCount" class="badge bg-danger" style="display:none;"></span>
                 </a>

                 <div class="dropdown-menu dropdown-menu-end p-2 shadow"
                     style="width: 330px; max-height: 450px; overflow-y: auto;"
                     id="notifyList">
                     <div class="text-center text-muted small">Loading...</div>
                 </div>
             </div>




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
 <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
    const pusher = new Pusher("<?= getenv('pusher.key') ?>", {
        cluster: "<?= getenv('pusher.cluster') ?>",
        encrypted: true
    });

    const channel = pusher.subscribe("admin-notifications");
    channel.bind("new-notification", function(data) {
        Swal.fire(data.title, data.message, "info");
        loadNotifications();
    });

    // Build one notification item
    function notificationItem(n) {
        // class based on is_read

        let notifyClass = n.is_read == 0 ? "unread-notify" : "read-notify";
        // badge based on is_read
        let badge = (n.is_read == 0)
            ? '<span class="badge bg-primary ms-2">New</span>'
            : '<span class="badge bg-secondary ms-2">Read</span>';

        return `
            <div class="d-flex align-items-center p-2 mb-1 rounded ${notifyClass}"
                style="cursor:pointer;"
                onclick="readNotification(${n.id}, event)">
                <div class="flex-grow-1">
                    <div class="small text-dark fw-semibold d-flex align-items-center">
                        ${n.message} ${badge}
                    </div>
                    <div class="text-muted small">
                        ${new Date(n.created_at).toLocaleString()}
                    </div>
                </div>
            </div>
        `;
    }

    // Load notifications + update counter
    function loadNotifications() {
        fetch("<?= base_url('admin/notifications/list'); ?>")
            .then(res => res.json())
            .then(data => {
                // if backend sends {notifications: [...]}
                if (!Array.isArray(data) && data.notifications) {
                    data = data.notifications;
                }

                // unread count
                let unread = data.filter(n => n.is_read == 0).length;
                let countEl = document.getElementById("notifyCount");

                if (unread > 0) {
                    countEl.style.display = "inline-block";
                    countEl.innerText = unread;
                } else {
                    countEl.style.display = "none";
                }

                // list
                let html = "";
                if (data.length > 0) {
                    data.forEach(n => html += notificationItem(n));
                    html += `<a href="<?= base_url('admin/app-notifications'); ?>" class="btn btn-sm btn-primary w-100 mt-2">View All Notifications</a>`;
                } else {
                    html = `<div class="text-center text-muted p-3">No notifications</div>`;
                }

                document.getElementById("notifyList").innerHTML = html;
            })
            .catch(err => {
                console.error("Notification load error:", err);
            });
    }

    // Only mark as read (no redirect)
    function readNotification(id, event) {
        event.stopPropagation(); // keep dropdown open

        fetch("<?= base_url('admin/notifications/mark-read'); ?>/" + id)
            .then(() => loadNotifications()); // reload list + counter
    }

    // Initial load
    loadNotifications();
</script>
