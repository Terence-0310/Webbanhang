<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="app-card text-center py-5 px-4">
    <div class="mb-3">
        <i class="bi bi-check-circle-fill" style="font-size: 4rem; color: var(--success);"></i>
    </div>
    <h1 class="h3 mb-2">Đặt hàng thành công!</h1>
    <p class="lead text-muted mb-4">
        Cảm ơn bạn đã đặt hàng. Đơn hàng đã được ghi nhận và đang chờ xử lý.
    </p>
    <a class="btn btn-primary" href="<?= base_url('Product') ?>">
        <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
    </a>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>
