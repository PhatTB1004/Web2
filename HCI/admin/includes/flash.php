<?php if (!empty($flash)): ?>
    <div class="flash-wrap mb-3">
        <div class="alert alert-<?php echo h($flash['type']); ?> mb-0 rounded-0 text-center">
            <?php echo h($flash['message']); ?>
        </div>
    </div>
<?php endif; ?>