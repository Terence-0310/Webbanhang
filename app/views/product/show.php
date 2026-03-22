<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="app-card p-3 p-md-4">
    <div class="row g-4 align-items-start">
        <!-- Ảnh sản phẩm -->
        <div class="col-md-5">
            <?php if (!empty($product->image)): ?>
                <img src="<?= base_url($product->image) ?>"
                     alt="<?= e($product->name) ?>"
                     class="img-fluid rounded-3 border w-100"
                     style="max-height: 420px; object-fit: cover;">
            <?php else: ?>
                <div class="placeholder-thumb w-100 rounded-3" style="height: 320px;">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                </div>
            <?php endif; ?>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-7">
            <span class="category-badge mb-2">
                <?= e($product->category_name ?? 'Chưa phân loại') ?>
            </span>

            <h1 class="h3 mt-2 mb-2"><?= e($product->name) ?></h1>

            <p class="price-tag fs-3 mb-3"><?= format_price($product->price) ?>₫</p>

            <div class="mb-4">
                <h2 class="h6 text-muted mb-2">Mô tả sản phẩm</h2>
                <p class="text-muted"><?= nl2br(e($product->description)) ?></p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-success" href="<?= base_url('Product/addToCart/' . $product->id) ?>">
                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                </a>
                <?php if (isAdmin()): ?>
                <a class="btn btn-outline-primary" href="<?= base_url('Product/edit/' . $product->id) ?>">
                    <i class="bi bi-pencil"></i> Sửa sản phẩm
                </a>
                <?php endif; ?>
                <a class="btn btn-secondary" href="<?= base_url('Product') ?>">
                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>
