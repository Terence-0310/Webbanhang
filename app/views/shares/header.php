<?php
/**
 * header.php – Layout chung: Phần đầu trang (HTML head + Navbar + Hero).
 *
 * Các biến được truyền vào:
 *   - $pageTitle (string): Tiêu đề trang
 */
$pageTitle = $pageTitle ?? 'Cửa hàng trực tuyến';
$cart      = $_SESSION['cart'] ?? [];
$cartCount = 0;
foreach ($cart as $item) {
    $cartCount += (int)($item['quantity'] ?? 0);
}
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cửa hàng trực tuyến – Mua sắm laptop, điện thoại và phụ kiện công nghệ">
    <title><?= e($pageTitle) ?> | MyStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* ===== Design Tokens ===== */
        :root {
            --brand:       #4f46e5;
            --brand-hover: #4338ca;
            --brand-light: #eef2ff;
            --surface:     #ffffff;
            --bg:          #f8fafc;
            --text:        #1e293b;
            --muted:       #64748b;
            --border:      #e2e8f0;
            --success:     #16a34a;
            --danger:      #dc2626;
            --radius:      12px;
            --shadow:      0 1px 3px rgba(0,0,0,.06), 0 6px 16px rgba(0,0,0,.04);
            --shadow-lg:   0 4px 12px rgba(0,0,0,.08), 0 16px 40px rgba(0,0,0,.06);
            --transition:  .2s ease;
        }

        /* ===== Base ===== */
        * { box-sizing: border-box; }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
        }

        /* ===== Navbar ===== */
        .app-navbar {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            box-shadow: 0 4px 20px rgba(30, 27, 75, .3);
            padding: .75rem 0;
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -.3px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar-brand i { font-size: 1.4rem; }
        .nav-link {
            color: rgba(255,255,255,.8) !important;
            font-weight: 500;
            padding: .5rem .9rem !important;
            border-radius: 8px;
            transition: var(--transition);
        }
        .nav-link:hover,
        .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,.1);
        }
        .cart-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: #fbbf24;
            color: #1e1b4b;
        }

        /* ===== Cards ===== */
        .app-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        .app-card:hover { box-shadow: var(--shadow-lg); }

        /* ===== Product Card ===== */
        .product-card { overflow: hidden; height: 100%; display: flex; flex-direction: column; }
        .product-cover { height: 220px; object-fit: cover; width: 100%; }
        .product-card .card-body { flex: 1; display: flex; flex-direction: column; }
        .product-card .card-actions { margin-top: auto; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ===== Thumbnails ===== */
        .product-thumb {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--border);
        }
        .table-thumb {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border);
        }
        .placeholder-thumb {
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--brand-light);
            border: 2px dashed var(--border);
            border-radius: 10px;
            color: var(--muted);
            font-size: .85rem;
        }

        /* ===== Buttons ===== */
        .btn-primary {
            background: var(--brand);
            border-color: var(--brand);
        }
        .btn-primary:hover {
            background: var(--brand-hover);
            border-color: var(--brand-hover);
        }

        /* ===== Section ===== */
        .section-title {
            font-weight: 700;
            letter-spacing: -.2px;
        }
        .page-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.5rem;
        }

        /* ===== Tables ===== */
        .table { margin-bottom: 0; }
        .table th { font-weight: 600; font-size: .85rem; text-transform: uppercase; letter-spacing: .3px; color: var(--muted); }

        /* ===== Forms ===== */
        .form-label { font-weight: 600; font-size: .9rem; margin-bottom: .35rem; }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .15);
        }

        /* ===== Price ===== */
        .price-tag {
            font-weight: 700;
            color: var(--brand);
            font-size: 1.1rem;
        }

        /* ===== Category Badge ===== */
        .category-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: .78rem;
            font-weight: 600;
            background: var(--brand-light);
            color: var(--brand);
        }

        /* ===== Footer ===== */
        .app-footer {
            margin-top: 3rem;
            padding: 1.5rem 0;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--muted);
            font-size: .85rem;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark app-navbar mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('Product') ?>">
            <i class="bi bi-shop"></i> MyStore
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Product') ?>">
                        <i class="bi bi-box-seam"></i> Sản phẩm
                    </a>
                </li>
                <?php if (isAdmin()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('Category') ?>">
                        <i class="bi bi-tags"></i> Danh mục
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center gap-2" href="<?= base_url('Product/cart') ?>">
                        <i class="bi bi-cart3"></i> Giỏ hàng
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-badge"><?= e((string)$cartCount) ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php if (isLoggedIn()): ?>
                    <?php $user = currentUser(); ?>
                    <li class="nav-item">
                        <span class="nav-link d-flex align-items-center gap-1">
                            <i class="bi bi-person-circle"></i>
                            <?= e($user['fullname']) ?>
                            <?php if (isAdmin()): ?>
                                <span class="badge bg-warning text-dark" style="font-size:.65rem;">Admin</span>
                            <?php endif; ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('Account/logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Đăng xuất
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('Account/login') ?>">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('Account/register') ?>">
                            <i class="bi bi-person-plus"></i> Đăng ký
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container">
    <!-- Flash Messages -->
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
