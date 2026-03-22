<?php include __DIR__ . '/../shares/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="h3 section-title mb-1">
            <i class="bi bi-box-seam"></i> Danh sách sản phẩm
        </h1>
        <p class="text-muted mb-0">
            Tổng <strong><?= e((string)count($products)) ?></strong> sản phẩm đang hiển thị
        </p>
    </div>
    <div class="d-flex gap-2">
        <?php if (isAdmin()): ?>
        <a class="btn btn-outline-secondary" href="<?= base_url('Category') ?>">
            <i class="bi bi-tags"></i> Quản lý danh mục
        </a>
        <a class="btn btn-primary" href="<?= base_url('Product/add') ?>">
            <i class="bi bi-plus-lg"></i> Thêm sản phẩm
        </a>
        <?php endif; ?>
    </div>
</div>

<?php if (empty($products)): ?>
    <!-- Empty State -->
    <div class="app-card p-5 text-center">
        <i class="bi bi-inbox" style="font-size: 3rem; color: var(--muted);"></i>
        <h2 class="h5 mt-3 mb-2">Chưa có sản phẩm nào</h2>
        <p class="text-muted mb-3">Hãy thêm sản phẩm đầu tiên để bắt đầu bán hàng.</p>
        <a class="btn btn-primary" href="<?= base_url('Product/add') ?>">
            <i class="bi bi-plus-lg"></i> Thêm sản phẩm mới
        </a>
    </div>
<?php else: ?>
    <!-- Product Grid -->
    <div class="row g-3">
        <?php foreach ($products as $product): ?>
            <div class="col-12 col-md-6 col-xl-4">
                <div class="app-card product-card">
                    <!-- Ảnh sản phẩm -->
                    <?php if (!empty($product->image)): ?>
                        <img src="<?= base_url($product->image) ?>"
                             alt="<?= e($product->name) ?>"
                             class="product-cover"
                             loading="lazy">
                    <?php else: ?>
                        <div class="product-cover d-flex align-items-center justify-content-center"
                             style="background: var(--brand-light);">
                            <i class="bi bi-image" style="font-size: 2.5rem; color: var(--muted);"></i>
                        </div>
                    <?php endif; ?>

                    <!-- Thông tin sản phẩm -->
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h2 class="h6 mb-0"><?= e($product->name) ?></h2>
                            <span class="category-badge"><?= e($product->category_name ?? 'Chưa phân loại') ?></span>
                        </div>

                        <p class="text-muted small mb-3 line-clamp-2"><?= e($product->description) ?></p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="price-tag"><?= format_price($product->price) ?>₫</span>
                            <a href="<?= base_url('Product/show/' . $product->id) ?>"
                               class="small text-decoration-none">
                                Xem chi tiết <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <!-- Actions -->
                        <div class="card-actions d-grid gap-2">
                            <a class="btn btn-success btn-sm" href="<?= base_url('Product/addToCart/' . $product->id) ?>">
                                <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                            </a>
                            <?php if (isAdmin()): ?>
                            <div class="btn-group btn-group-sm">
                                <a class="btn btn-outline-primary" href="<?= base_url('Product/edit/' . $product->id) ?>">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <a class="btn btn-outline-danger"
                                   href="<?= base_url('Product/delete/' . $product->id) ?>"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../shares/footer.php'; ?>
