<?php include __DIR__ . '/../shares/header.php'; ?>

<h1 class="h3 section-title mb-3">
    <i class="bi bi-pencil-square"></i> Sửa sản phẩm
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

<!-- Form sửa sản phẩm -->
<form method="POST" action="<?= base_url('Product/update/' . $product->id) ?>"
      enctype="multipart/form-data" class="app-card p-4">

    <input type="hidden" name="existing_image" value="<?= e($product->image ?? '') ?>">

    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control"
                       value="<?= e($product->name) ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                <textarea id="description" name="description" class="form-control" rows="4"
                          required><?= e($product->description) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                    <input type="number" id="price" name="price" class="form-control"
                           step="1000" min="0" value="<?= e($product->price) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= e($category->id) ?>"
                                <?= ((int)($product->category_id ?? 0) === (int)$category->id) ? 'selected' : '' ?>>
                                <?= e($category->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label">Hình ảnh hiện tại</label><br>
                <?php if (!empty($product->image)): ?>
                    <img src="<?= base_url($product->image) ?>"
                         alt="<?= e($product->name ?? '') ?>"
                         class="product-thumb mb-2">
                <?php else: ?>
                    <div class="placeholder-thumb mb-2" style="width: 110px; height: 110px;">
                        <i class="bi bi-image"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Tải ảnh mới (tuỳ chọn)</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                <div class="form-text">Để trống nếu không muốn thay đổi ảnh.</div>
            </div>
        </div>
    </div>

    <hr>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Lưu thay đổi
        </button>
        <a href="<?= base_url('Product') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</form>

<?php include __DIR__ . '/../shares/footer.php'; ?>
