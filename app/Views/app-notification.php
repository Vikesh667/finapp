<?php echo view('header'); ?>
<body>
    <div id="loader">
    <img src="<?= base_url('assets/img/logo.png') ;?>" class="loader-logo">
</div>
<?php echo view('topHeader'); ?>

<div id="appCapsule">

    <div class="section mt-4 mb-4">
        <div class="section-heading">
            <h2 class="title">Notifications</h2>
        </div>
        <div class="notify-wrapper">
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $note): ?>
                    <a href="<?= base_url('admin/notification/view/' . $note['id']); ?>" class="notify-row mb-2">
                        <div class="notify-details">
                            <strong class="<?= $note['is_read'] == 0 ? 'text-primary' : '' ?>">
                                <?= esc($note['message']) ?>
                            </strong>

                            <p class="m-0 text-muted small">
                                <ion-icon name="time-outline"></ion-icon>
                                <?= date("d M Y • h:i A", strtotime($note['created_at'])) ?>
                            </p>
                        </div>

                        <div class="notify-right text-end">
                            <?php if ($note['is_read'] == 0): ?>
                                <span class="badge bg-danger">New</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Read</span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center mt-4">
                    <h5 class="text-muted">No Notifications Found</h5>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php echo view('sidebar'); ?>
<?php echo view('bottomMenu'); ?>
<?php echo view('footerlink'); ?>
