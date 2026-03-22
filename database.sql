-- ============================================================
-- DATABASE: my_store
-- Mô tả: Schema cho ứng dụng Web bán hàng MVC PHP
-- Bài thực hành: [COS340] Phát triển phần mềm mã nguồn mở
-- ============================================================

CREATE DATABASE IF NOT EXISTS my_store
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE my_store;

-- ------------------------------------------------------------
-- Bảng: account (Tài khoản người dùng)
-- Vai trò: 'admin' – quản trị viên, 'user' – khách hàng
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS account (
    id          INT             AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(100)    NOT NULL,
    fullname    VARCHAR(255)    NOT NULL DEFAULT '',
    password    VARCHAR(255)    NOT NULL  COMMENT 'Mã hóa bằng password_hash()',
    role        ENUM('user','admin') NOT NULL DEFAULT 'user',
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uk_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Bảng: categories (Danh mục sản phẩm)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categories (
    id          INT             AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255)    NOT NULL,
    description TEXT            NULL,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Tránh trùng tên danh mục
    UNIQUE KEY uk_category_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Bảng: products (Sản phẩm)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
    id          INT             AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255)    NOT NULL,
    description TEXT            NOT NULL,
    price       DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
    image       VARCHAR(500)    NULL      COMMENT 'Đường dẫn tương đối tới file ảnh hoặc URL',
    category_id INT             NOT NULL,
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Foreign key liên kết tới bảng categories
    INDEX       idx_product_category (category_id),
    CONSTRAINT  fk_product_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Bảng: orders (Đơn hàng)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
    id          INT             AUTO_INCREMENT PRIMARY KEY,
    customer_name   VARCHAR(255)    NOT NULL,
    phone       VARCHAR(20)     NOT NULL,
    address     TEXT            NOT NULL,
    status      ENUM('pending','processing','completed','cancelled')
                                NOT NULL DEFAULT 'pending',
    created_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Bảng: order_details (Chi tiết đơn hàng)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS order_details (
    id          INT             AUTO_INCREMENT PRIMARY KEY,
    order_id    INT             NOT NULL,
    product_id  INT             NOT NULL,
    quantity    INT UNSIGNED    NOT NULL DEFAULT 1,
    price       DECIMAL(12,2)   NOT NULL  COMMENT 'Giá tại thời điểm đặt hàng',

    INDEX       idx_od_order   (order_id),
    INDEX       idx_od_product (product_id),

    CONSTRAINT  fk_od_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT  fk_od_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- DỮ LIỆU MẪU (Seed Data)
-- ============================================================

-- Danh mục mẫu
INSERT IGNORE INTO categories (name, description) VALUES
    ('Laptop',      'Máy tính xách tay các hãng'),
    ('Smartphone',  'Điện thoại thông minh'),
    ('Phụ kiện',    'Phụ kiện công nghệ: chuột, bàn phím, tai nghe');

-- Sản phẩm mẫu – Laptop
INSERT INTO products (name, description, price, image, category_id)
SELECT 'MacBook Air M2 13 inch',
       'Laptop mỏng nhẹ, pin tốt, phù hợp học tập và làm việc văn phòng.',
       1099.00,
       'https://images.unsplash.com/photo-1517336714739-489689fd1ca8?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Laptop'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'MacBook Air M2 13 inch')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Dell XPS 13',
       'Ultrabook cao cấp với màn hình đẹp, thiết kế gọn gàng.',
       1249.00,
       'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Laptop'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Dell XPS 13')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'ASUS ROG Zephyrus G14',
       'Laptop gaming hiệu năng cao, phù hợp cho cả game và đồ hoạ.',
       1599.00,
       'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Laptop'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'ASUS ROG Zephyrus G14')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'HP Pavilion 15',
       'Laptop tầm trung với hiệu năng ổn định cho nhu cầu hàng ngày.',
       799.00,
       'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Laptop'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'HP Pavilion 15')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Lenovo ThinkPad X1 Carbon',
       'Dòng máy bền bỉ cho doanh nghiệp, bàn phím tốt và bảo mật cao.',
       1399.00,
       'https://images.unsplash.com/photo-1504707748692-419802cf939d?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Laptop'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Lenovo ThinkPad X1 Carbon')
LIMIT 1;

-- Sản phẩm mẫu – Smartphone
INSERT INTO products (name, description, price, image, category_id)
SELECT 'iPhone 15 Pro',
       'Điện thoại cao cấp của Apple với camera và hiệu năng mạnh.',
       999.00,
       'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Smartphone'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'iPhone 15 Pro')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Samsung Galaxy S24',
       'Flagship Android với màn hình sáng đẹp và camera linh hoạt.',
       899.00,
       'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Smartphone'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Samsung Galaxy S24')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Google Pixel 8',
       'Smartphone chụp ảnh đẹp, Android thuần Google.',
       699.00,
       'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Smartphone'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Google Pixel 8')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Xiaomi 14',
       'Điện thoại hiệu năng cao, giá cạnh tranh, pin tốt.',
       649.00,
       'https://images.unsplash.com/photo-1580910051074-3eb694886505?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Smartphone'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Xiaomi 14')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'OPPO Reno 11',
       'Thiết kế đẹp, selfie tốt, phù hợp người dùng trẻ.',
       499.00,
       'https://images.unsplash.com/photo-1567581935884-3349723552ca?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Smartphone'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'OPPO Reno 11')
LIMIT 1;

-- ============================================================
-- TÀI KHOẢN MẪU
-- Admin: admin / admin123
-- User:  user  / user123
-- (Mật khẩu đã mã hóa bằng password_hash với bcrypt)
-- ============================================================
INSERT INTO account (username, fullname, password, role)
SELECT 'admin', 'Quản trị viên', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM account WHERE username = 'admin');

INSERT INTO account (username, fullname, password, role)
SELECT 'user', 'Người dùng', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM account WHERE username = 'user');
