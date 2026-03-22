-- ============================================================
-- SEED DATA – Dữ liệu mẫu cho bảng categories & products
-- Chạy file này SAU KHI đã chạy database.sql
-- ============================================================

USE my_store;

-- Danh mục mẫu
INSERT IGNORE INTO categories (name, description) VALUES
    ('Laptop',      'Máy tính xách tay các hãng'),
    ('Smartphone',  'Điện thoại thông minh'),
    ('Phụ kiện',    'Phụ kiện công nghệ: chuột, bàn phím, tai nghe');

-- Sản phẩm mẫu – Laptop
INSERT INTO products (name, description, price, image, category_id)
SELECT 'MacBook Air M2 13 inch',
       'Laptop mỏng nhẹ, pin tốt, phù hợp học tập và làm việc văn phòng.',
       27000000,
       'https://images.unsplash.com/photo-1517336714739-489689fd1ca8?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Laptop'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'MacBook Air M2 13 inch')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Dell XPS 13',
       'Ultrabook cao cấp với màn hình đẹp, thiết kế gọn gàng.',
       31000000,
       'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Laptop'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Dell XPS 13')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'ASUS ROG Zephyrus G14',
       'Laptop gaming hiệu năng cao, phù hợp cho cả game và đồ hoạ.',
       39000000,
       'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Laptop'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'ASUS ROG Zephyrus G14')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'HP Pavilion 15',
       'Laptop tầm trung với hiệu năng ổn định cho nhu cầu hàng ngày.',
       18000000,
       'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Laptop'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'HP Pavilion 15')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Lenovo ThinkPad X1 Carbon',
       'Dòng máy bền bỉ cho doanh nghiệp, bàn phím tốt và bảo mật cao.',
       35000000,
       'https://images.unsplash.com/photo-1504707748692-419802cf939d?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Laptop'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Lenovo ThinkPad X1 Carbon')
LIMIT 1;

-- Sản phẩm mẫu – Smartphone
INSERT INTO products (name, description, price, image, category_id)
SELECT 'iPhone 15 Pro',
       'Điện thoại cao cấp của Apple với camera và hiệu năng mạnh.',
       28000000,
       'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Smartphone'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'iPhone 15 Pro')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Samsung Galaxy S24',
       'Flagship Android với màn hình sáng đẹp và camera linh hoạt.',
       22000000,
       'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Smartphone'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Samsung Galaxy S24')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Google Pixel 8',
       'Smartphone chụp ảnh đẹp, Android thuần Google.',
       17000000,
       'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Smartphone'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Google Pixel 8')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Xiaomi 14',
       'Điện thoại hiệu năng cao, giá cạnh tranh, pin tốt.',
       14000000,
       'https://images.unsplash.com/photo-1580910051074-3eb694886505?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Smartphone'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Xiaomi 14')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'OPPO Reno 11',
       'Thiết kế đẹp, selfie tốt, phù hợp người dùng trẻ.',
       10000000,
       'https://images.unsplash.com/photo-1567581935884-3349723552ca?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Smartphone'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'OPPO Reno 11')
LIMIT 1;

-- Sản phẩm mẫu – Phụ kiện
INSERT INTO products (name, description, price, image, category_id)
SELECT 'Chuột Logitech MX Master 3S',
       'Chuột không dây cao cấp, ergonomic, cuộn siêu mượt.',
       2500000,
       'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Phụ kiện'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Chuột Logitech MX Master 3S')
LIMIT 1;

INSERT INTO products (name, description, price, image, category_id)
SELECT 'Tai nghe Sony WH-1000XM5',
       'Tai nghe chống ồn chủ động, chất âm Hi-Res, pin 30 giờ.',
       8000000,
       'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=900&q=80',
       c.id
FROM categories c WHERE c.name = 'Phụ kiện'
  AND NOT EXISTS (SELECT 1 FROM products WHERE name = 'Tai nghe Sony WH-1000XM5')
LIMIT 1;
