<?php include __DIR__ . '/../shares/header.php'; ?>

<h1 class="h3 section-title mb-3">
    <i class="bi bi-pencil-square"></i> Sửa danh mục
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

<!-- Form sửa danh mục -->
<form method="POST" action="<?= base_url('Category/update/' . $category->id) ?>" class="app-card p-4">
    <div class="mb-3">
        <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control"
               value="<?= e($category->name) ?>" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea id="description" name="description" class="form-control"
                  rows="4"><?= e($category->description) ?></textarea>
    </div>

    <hr>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Lưu thay đổi
        </button>
        <a href="<?= base_url('Category') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</form>

<?php include __DIR__ . '/../shares/footer.php'; ?>
