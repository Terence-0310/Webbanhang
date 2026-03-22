<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="app-card p-4">
            <div class="text-center mb-4">
                <i class="bi bi-person-plus" style="font-size: 3rem; color: var(--brand);"></i>
                <h1 class="h4 mt-2 mb-1">Đăng ký tài khoản</h1>
                <p class="text-muted small">Tạo tài khoản mới để mua sắm trực tuyến</p>
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

            <!-- Form đăng ký -->
            <form method="POST" action="<?= base_url('Account/processRegister') ?>">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                        <input type="text" id="fullname" name="fullname" class="form-control"
                               value="<?= old('fullname') ?>" placeholder="Nhập họ tên đầy đủ" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" id="username" name="username" class="form-control"
                               value="<?= old('username') ?>" placeholder="Chỉ chữ cái, số, dấu gạch dưới" required>
                    </div>
                    <div class="form-text">Tối thiểu 3 ký tự, chỉ chứa chữ cái, số và dấu gạch dưới (_).</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Tối thiểu 6 ký tự" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                               placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Đăng ký
                    </button>
                </div>

                <div class="text-center">
                    <span class="text-muted small">Đã có tài khoản?</span>
                    <a href="<?= base_url('Account/login') ?>" class="small text-decoration-none">
                        Đăng nhập
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shares/footer.php'; ?>
