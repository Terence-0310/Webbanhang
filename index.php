<?php
/**
 * index.php – Front Controller (Router chính)
 *
 * Mọi request đều đi qua file này nhờ .htaccess rewrite.
 * Phân tích URL thành: Controller / Action / Params
 *
 * Ví dụ:
 *   /Product/show/5  →  ProductController::show(5)
 *   /Category/edit/3 →  CategoryController::edit(3)
 *   /                →  ProductController::index()
 */

// 1. Khởi tạo session
session_start();

// 2. Load helpers (các hàm tiện ích dùng chung)
require_once __DIR__ . '/app/config/helpers.php';

// 3. Phân tích URL
$url   = $_GET['url'] ?? 'Product/index';
$url   = trim($url, '/');
$url   = filter_var($url, FILTER_SANITIZE_URL);
$parts = array_values(array_filter(explode('/', $url), fn($part) => $part !== ''));

// 4. Xác định Controller, Action, Params
$controllerName = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'ProductController';
$action         = $parts[1] ?? 'index';
$params         = array_slice($parts, 2);

// 5. Kiểm tra file controller tồn tại
$controllerFile = __DIR__ . '/app/controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    http_response_code(404);
    exit('Không tìm thấy trang bạn yêu cầu.');
}

require_once $controllerFile;

// 6. Kiểm tra class tồn tại
if (!class_exists($controllerName)) {
    http_response_code(404);
    exit('Controller không hợp lệ.');
}

$controller = new $controllerName();

// 7. Kiểm tra action (method) tồn tại
if (!method_exists($controller, $action)) {
    http_response_code(404);
    exit('Không tìm thấy chức năng bạn yêu cầu.');
}

// 8. Gọi action với tham số
call_user_func_array([$controller, $action], $params);
