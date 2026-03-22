<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="app-card p-4">
            <div class="text-center mb-4">
                <i class="bi bi-person-circle" style="font-size: 3rem; color: var(--brand);"></i>
                <h1 class="h4 mt-2 mb-1">Đăng nhập</h1>
                <p class="text-muted small">Nhập thông tin tài khoản để tiếp tục</p>
            </div>

            <!-- Hiển thị lỗi -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 small">
                        <?php foreach ($errors as $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Form đăng nhập -->
            <form method="POST" action="<?= base_url('Account/processLogin') ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" id="username" name="username" class="form-control"
                               value="<?= old('username') ?>" placeholder="Nhập tên đăng nhập" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Nhập mật khẩu" required>
                    </div>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                    </button>
                </div>

                <div class="text-center">
                    <span class="text-muted small">Chưa có tài khoản?</span>
                    <a href="<?= base_url('Account/register') ?>" class="small text-decoration-none">
                        Đăng ký ngay
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>
