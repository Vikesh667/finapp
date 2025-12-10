<?= view('header'); ?>
<?= view('topHeader'); ?>

<div id="appCapsule">
    
    <div class="section mt-4 col-12 col-md-6">
        <h3 class="fw-bold text-dark"><?= esc($notification['message']) ?></h3>
        <p class="text-muted mt-2">
            <ion-icon name="time-outline"></ion-icon>
            <?= date("d M Y • h:i A", strtotime($notification['created_at'])) ?>
        </p>
        <a href="<?= base_url('admin/app-notifications'); ?>" class="btn btn-primary mt-3">
            Back to Notifications
        </a>
    </div>
  
</div>

<?= view('footerlink'); ?>
