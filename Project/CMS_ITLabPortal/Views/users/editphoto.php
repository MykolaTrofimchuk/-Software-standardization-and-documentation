<div class="modal-content rounded-4 shadow">
    <div class="modal-body p-5 pt-0">
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
    </div>
</div>
