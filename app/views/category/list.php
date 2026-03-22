<?php include __DIR__ . '/../shares/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h1 class="h3 section-title mb-0">
        <i class="bi bi-tags"></i> Quản lý danh mục
    </h1>
    <a class="btn btn-primary" href="<?= base_url('Category/add') ?>">
        <i class="bi bi-plus-lg"></i> Thêm danh mục
    </a>
</div>

<!-- Bảng danh mục -->
<div class="app-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th class="text-end" style="width: 140px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox"></i> Chưa có danh mục nào.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= e($category->id) ?></td>
                        <td><strong><?= e($category->name) ?></strong></td>
                        <td class="text-muted"><?= e($category->description) ?></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a class="btn btn-outline-primary"
                                   href="<?= base_url('Category/edit/' . $category->id) ?>"
                                   title="Sửa danh mục">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <a class="btn btn-outline-danger"
                                   href="<?= base_url('Category/delete/' . $category->id) ?>"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')"
                                   title="Xóa danh mục">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>
