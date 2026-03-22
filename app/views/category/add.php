<?php include __DIR__ . '/../shares/header.php'; ?>

<h1 class="h3 section-title mb-3">
    <i class="bi bi-plus-circle"></i> Thêm danh mục mới
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

<!-- Form thêm danh mục -->
<form method="POST" action="<?= base_url('Category/save') ?>" class="app-card p-4">
    <div class="mb-3">
        <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control"
               value="<?= old('name') ?>" placeholder="Nhập tên danh mục" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea id="description" name="description" class="form-control" rows="4"
                  placeholder="Nhập mô tả cho danh mục (không bắt buộc)"><?= old('description') ?></textarea>
    </div>

    <hr>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Lưu danh mục
        </button>
        <a href="<?= base_url('Category') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</form>

<?php include __DIR__ . '/../shares/footer.php'; ?>
