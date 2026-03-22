# MyStore – Web bán hàng MVC PHP

## Giới thiệu

Dự án **Web bán hàng** được xây dựng theo kiến trúc **MVC (Model-View-Controller)** với PHP thuần.  
Đây là bài thực hành môn **[COS340] Phát triển phần mềm mã nguồn mở** tại HUTECH.

## Tính năng

- **Đăng ký / Đăng nhập / Đăng xuất**: Hệ thống xác thực người dùng hoàn chỉnh
- **Phân quyền**: Admin quản trị (CRUD), User duyệt & mua hàng
- **Quản lý sản phẩm**: Thêm, sửa, xóa, xem chi tiết sản phẩm (CRUD đầy đủ – chỉ Admin)
- **Quản lý danh mục**: Thêm, sửa, xóa danh mục sản phẩm (chỉ Admin)
- **Upload hình ảnh**: Hỗ trợ upload ảnh sản phẩm (JPG, PNG, GIF, WEBP)
- **Giỏ hàng**: Thêm/xóa sản phẩm, tính tổng tiền (Session-based)
- **Đặt hàng**: Form thanh toán với validation, lưu đơn hàng vào CSDL
- **Flash Messages**: Thông báo thành công/lỗi hiển thị 1 lần
- **Mật khẩu mã hóa**: Sử dụng `password_hash` / `password_verify` (bcrypt)

## Tài khoản mẫu

| Vai trò | Username | Mật khẩu |
|---------|----------|-----------|
| Admin   | `admin`  | `password` |
| User    | `user`   | `password` |

## Cấu trúc thư mục

```
Webbanhang-main/
├── index.php                  # Front Controller (Router)
├── .htaccess                  # URL Rewriting
├── database.sql               # Schema + Seed Data
├── seed_products.sql          # Dữ liệu mẫu bổ sung
├── uploads/                   # Thư mục lưu ảnh upload
└── app/
    ├── config/
    │   ├── database.php       # Kết nối CSDL (Singleton)
    │   └── helpers.php        # Hàm tiện ích (URL, CSRF, Flash, Upload, Auth)
    ├── controllers/
    │   ├── AccountController.php   # Đăng nhập / Đăng ký / Đăng xuất
    │   ├── ProductController.php   # CRUD sản phẩm + Giỏ hàng + Đặt hàng
    │   └── CategoryController.php  # CRUD danh mục (Admin only)
    ├── models/
    │   ├── AccountModel.php   # Xác thực tài khoản
    │   ├── ProductModel.php   # Truy vấn sản phẩm
    │   ├── CategoryModel.php  # Truy vấn danh mục
    │   └── OrderModel.php     # Tạo đơn hàng
    └── views/
        ├── shares/
        │   ├── header.php     # Layout chung (head + navbar + flash)
        │   └── footer.php     # Footer + scripts
        ├── account/
        │   ├── login.php      # Trang đăng nhập
        │   └── register.php   # Trang đăng ký
        ├── product/
        │   ├── list.php       # Danh sách sản phẩm
        │   ├── show.php       # Chi tiết sản phẩm
        │   ├── add.php        # Form thêm sản phẩm (Admin)
        │   ├── edit.php       # Form sửa sản phẩm (Admin)
        │   ├── cart.php       # Giỏ hàng
        │   ├── checkout.php   # Thanh toán
        │   └── orderConfirmation.php
        └── category/
            ├── list.php       # Danh sách danh mục (Admin)
            ├── add.php        # Form thêm danh mục (Admin)
            └── edit.php       # Form sửa danh mục (Admin)
```

## Yêu cầu hệ thống

- **PHP** >= 8.0
- **MySQL** >= 5.7 hoặc **MariaDB** >= 10.3
- **Apache** với `mod_rewrite` enabled (Laragon, XAMPP, WAMP, ...)

## Hướng dẫn cài đặt

### 1. Clone dự án

```bash
git clone <repository-url>
```

### 2. Tạo CSDL

Import file `database.sql` vào MySQL:

```bash
mysql -u root < database.sql
```

Hoặc sử dụng phpMyAdmin để import.

### 3. Cấu hình kết nối CSDL

Mở file `app/config/database.php` và chỉnh sửa thông tin nếu cần:

```php
private const HOST     = '127.0.0.1';
private const DB_NAME  = 'my_store';
private const USERNAME = 'root';
private const PASSWORD = '';
```

### 4. Chạy ứng dụng

Đặt thư mục dự án vào `www` (Laragon) hoặc `htdocs` (XAMPP), sau đó truy cập:

```
http://localhost/Webbanhang-main/Webbanhang-main/
```

## Database Schema

| Bảng | Mô tả |
|------|-------|
| `account` | Tài khoản (id, username, fullname, password, role, timestamps) |
| `categories` | Danh mục sản phẩm (id, name, description, timestamps) |
| `products` | Sản phẩm (id, name, description, price, image, category_id, timestamps) |
| `orders` | Đơn hàng (id, customer_name, phone, address, status, timestamps) |
| `order_details` | Chi tiết đơn hàng (id, order_id, product_id, quantity, price) |

## Luồng phân quyền

```
Khách (chưa đăng nhập)    → Xem sản phẩm, Thêm giỏ hàng
User  (đã đăng nhập)      → Xem, Mua hàng, Đặt hàng
Admin (role = 'admin')     → Tất cả + CRUD sản phẩm/danh mục
```

## Kiến trúc MVC

- **Model**: Xử lý dữ liệu, truy vấn CSDL (PDO + Prepared Statements)
- **View**: Giao diện hiển thị (Bootstrap 5 + Bootstrap Icons)
- **Controller**: Điều phối logic, nhận request → gọi Model → render View

## Bảo mật

- Mật khẩu mã hóa bcrypt (`password_hash` / `password_verify`)
- Prepared Statements chống SQL Injection
- `htmlspecialchars()` chống XSS
- CSRF Token cho forms (đã triển khai helper)
- Session-based authentication với phân quyền

## Tác giả

Bài thực hành [COS340] – HUTECH
