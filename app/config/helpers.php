<?php
/**
 * helpers.php – Các hàm tiện ích dùng chung trong toàn bộ ứng dụng.
 *
 * Được include 1 lần duy nhất tại index.php (front-controller).
 */

/* ================================================================
 * URL & Path
 * ================================================================ */

/**
 * Trả về base path của ứng dụng (không có trailing slash).
 */
function app_base_path(): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $base = str_replace('\\', '/', dirname($scriptName));
    return rtrim($base === '/' ? '' : $base, '/');
}

/**
 * Tạo URL tuyệt đối dựa trên base path.
 * Nếu $path đã là URL đầy đủ (http/https) thì trả nguyên.
 */
function base_url(string $path = ''): string
{
    if (preg_match('/^https?:\/\//i', $path)) {
        return $path;
    }
    $base = app_base_path();
    $path = ltrim($path, '/');
    return $base . ($path !== '' ? '/' . $path : '');
}

/**
 * Chuyển hướng (redirect) tới một đường dẫn trong ứng dụng.
 */
function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

/* ================================================================
 * Security & Encoding
 * ================================================================ */

/**
 * Escape HTML để chống XSS.
 */
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Tạo CSRF token và lưu vào session.
 */
function csrf_token(): string
{
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

/**
 * Tạo input hidden chứa CSRF token cho form.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Kiểm tra CSRF token từ POST request.
 * Trả về true nếu hợp lệ, false nếu không.
 */
function verify_csrf(): bool
{
    $token = $_POST['_csrf_token'] ?? '';
    $sessionToken = $_SESSION['_csrf_token'] ?? '';

    if ($token === '' || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
        return false;
    }

    // Xóa token sau khi dùng để tránh replay attack
    unset($_SESSION['_csrf_token']);
    return true;
}

/* ================================================================
 * Flash Messages
 * ================================================================ */

/**
 * Đặt flash message (hiển thị 1 lần duy nhất).
 *
 * @param string $type    Loại: 'success', 'danger', 'warning', 'info'
 * @param string $message Nội dung thông báo
 */
function set_flash(string $type, string $message): void
{
    $_SESSION['_flash'] = [
        'type'    => $type,
        'message' => $message,
    ];
}

/**
 * Lấy flash message (tự động xóa sau khi lấy).
 *
 * @return array|null ['type' => ..., 'message' => ...]
 */
function get_flash(): ?array
{
    if (isset($_SESSION['_flash'])) {
        $flash = $_SESSION['_flash'];
        unset($_SESSION['_flash']);
        return $flash;
    }
    return null;
}

/* ================================================================
 * Form Helpers
 * ================================================================ */

/**
 * Lấy giá trị cũ từ POST (dùng khi form validation thất bại).
 */
function old(string $key, $default = ''): string
{
    return e($_POST[$key] ?? $default);
}

/* ================================================================
 * Formatting
 * ================================================================ */

/**
 * Định dạng giá tiền kiểu Việt Nam (VNĐ).
 * Ví dụ: 1099.00 → "1,099.00"
 */
function format_price($price): string
{
    return number_format((float)$price, 0, ',', '.');
}

/* ================================================================
 * File Upload
 * ================================================================ */

/**
 * Xử lý upload hình ảnh sản phẩm.
 *
 * @param array  $file       Mảng $_FILES['image']
 * @param string $uploadDir  Thư mục lưu (mặc định: uploads/)
 * @param int    $maxSize    Dung lượng tối đa (bytes, mặc định 2MB)
 * @return string Đường dẫn tương đối tới file đã upload
 * @throws Exception Nếu file không hợp lệ
 */
function upload_image(array $file, string $uploadDir = 'uploads', int $maxSize = 2 * 1024 * 1024): string
{
    $targetDir = __DIR__ . '/../../' . $uploadDir;

    // Tạo thư mục nếu chưa tồn tại
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Kiểm tra file upload hợp lệ
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        throw new Exception('File tải lên không hợp lệ.');
    }

    // Kiểm tra có phải hình ảnh không
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        throw new Exception('File tải lên phải là hình ảnh.');
    }

    // Kiểm tra dung lượng
    if (($file['size'] ?? 0) > $maxSize) {
        $maxMB = $maxSize / (1024 * 1024);
        throw new Exception("Hình ảnh vượt quá {$maxMB}MB.");
    }

    // Kiểm tra phần mở rộng
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extension, $allowed, true)) {
        throw new Exception('Chỉ chấp nhận file: ' . implode(', ', array_map('strtoupper', $allowed)) . '.');
    }

    // Tạo tên file duy nhất
    $newName = uniqid('img_', true) . '.' . $extension;
    $targetPath = $targetDir . '/' . $newName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Không thể lưu hình ảnh. Vui lòng thử lại.');
    }

    return $uploadDir . '/' . $newName;
}

/* ================================================================
 * Authentication / Session Helpers
 * ================================================================ */

/**
 * Kiểm tra người dùng đã đăng nhập chưa.
 */
function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

/**
 * Kiểm tra người dùng hiện tại có phải Admin không.
 */
function isAdmin(): bool
{
    return isLoggedIn() && ($_SESSION['user_role'] ?? '') === 'admin';
}

/**
 * Lấy thông tin người dùng hiện tại từ session.
 *
 * @return array|null ['id', 'username', 'fullname', 'role']
 */
function currentUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id'       => $_SESSION['user_id'],
        'username' => $_SESSION['user_username'] ?? '',
        'fullname' => $_SESSION['user_fullname'] ?? '',
        'role'     => $_SESSION['user_role'] ?? 'user',
    ];
}

/**
 * Yêu cầu đăng nhập – redirect nếu chưa đăng nhập.
 */
function require_login(): void
{
    if (!isLoggedIn()) {
        set_flash('warning', 'Vui lòng đăng nhập để tiếp tục.');
        redirect('Account/login');
    }
}

/**
 * Yêu cầu quyền Admin – redirect nếu không phải admin.
 */
function require_admin(): void
{
    require_login();
    if (!isAdmin()) {
        set_flash('danger', 'Bạn không có quyền truy cập chức năng này.');
        redirect('Product');
    }
}
