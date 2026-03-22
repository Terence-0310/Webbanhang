<?php include __DIR__ . '/../shares/header.php'; ?>

<h1 class="h3 section-title mb-3">
    <i class="bi bi-credit-card"></i> Thanh toán
</h1>

<!-- Hiển thị lỗi -->
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Form thông tin giao hàng -->
    <div class="col-md-7">
        <form method="POST" action="<?= base_url('Product/processCheckout') ?>" class="app-card p-4">
            <h2 class="h5 mb-3">
                <i class="bi bi-truck"></i> Thông tin giao hàng
            </h2>

            <div class="mb-3">
                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control"
                       value="<?= old('name') ?>" placeholder="Nhập họ tên người nhận" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="tel" id="phone" name="phone" class="form-control"
                       value="<?= old('phone') ?>" placeholder="Nhập số điện thoại" required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                <textarea id="address" name="address" class="form-control" rows="3"
                          placeholder="Nhập địa chỉ chi tiết để giao hàng" required><?= old('address') ?></textarea>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Xác nhận đặt hàng
                </button>
                <a href="<?= base_url('Product/cart') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
                </a>
            </div>
        </form>
    </div>

    <!-- Tóm tắt đơn hàng -->
    <div class="col-md-5">
        <div class="app-card p-4">
            <h2 class="h5 mb-3">
                <i class="bi bi-receipt"></i> Tóm tắt đơn hàng
            </h2>
            <?php $total = 0; ?>
            <?php foreach ($cart as $id => $item): ?>
                <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <strong class="small"><?= e($item['name']) ?></strong>
                        <span class="text-muted small">× <?= e((string)$item['quantity']) ?></span>
                    </div>
                    <span class="small"><?= format_price($subtotal) ?>₫</span>
                </div>
            <?php endforeach; ?>
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2">
                <strong>Tổng cộng:</strong>
                <span class="price-tag"><?= format_price($total) ?>₫</span>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>
