<?php include __DIR__ . '/../shares/header.php'; ?>

<h1 class="h3 section-title mb-3">
    <i class="bi bi-cart3"></i> Giỏ hàng
</h1>

<?php if (empty($cart)): ?>
    <!-- Giỏ hàng trống -->
    <div class="app-card p-5 text-center">
        <i class="bi bi-cart-x" style="font-size: 3rem; color: var(--muted);"></i>
        <h2 class="h5 mt-3 mb-2">Giỏ hàng đang trống</h2>
        <p class="text-muted mb-3">Thêm một vài sản phẩm để trải nghiệm luồng thanh toán.</p>
        <a class="btn btn-primary" href="<?= base_url('Product') ?>">
            <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
        </a>
    </div>
<?php else: ?>
    <!-- Bảng giỏ hàng -->
    <div class="app-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Ảnh</th>
                        <th>Sản phẩm</th>
                        <th class="text-end">Đơn giá</th>
                        <th class="text-center" style="width: 100px;">Số lượng</th>
                        <th class="text-end">Thành tiền</th>
                        <th style="width: 60px;"></th>
                    </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                <?php foreach ($cart as $id => $item): ?>
                    <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
                    <tr>
                        <td>
                            <?php if (!empty($item['image'])): ?>
                                <img src="<?= base_url($item['image']) ?>"
                                     class="table-thumb"
                                     alt="<?= e($item['name']) ?>"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="table-thumb d-flex align-items-center justify-content-center"
                                     style="background: var(--brand-light);">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= e($item['name']) ?></strong></td>
                        <td class="text-end"><?= format_price($item['price']) ?>₫</td>
                        <td class="text-center"><?= e((string)$item['quantity']) ?></td>
                        <td class="text-end"><strong><?= format_price($subtotal) ?>₫</strong></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-danger"
                               href="<?= base_url('Product/removeFromCart/' . $id) ?>"
                               title="Xóa khỏi giỏ">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <th colspan="4" class="text-end">Tổng cộng:</th>
                        <th class="text-end price-tag"><?= format_price($total) ?>₫</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-3 d-flex justify-content-between">
        <a class="btn btn-secondary" href="<?= base_url('Product') ?>">
            <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
        </a>
        <a class="btn btn-success" href="<?= base_url('Product/checkout') ?>">
            <i class="bi bi-credit-card"></i> Thanh toán
        </a>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../shares/footer.php'; ?>
