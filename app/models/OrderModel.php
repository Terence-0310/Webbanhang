<?php
/**
 * OrderModel – Xử lý dữ liệu bảng `orders` và `order_details`.
 *
 * Sử dụng Transaction để đảm bảo tính toàn vẹn dữ liệu
 * khi tạo đơn hàng cùng chi tiết.
 */

require_once __DIR__ . '/../config/database.php';

class OrderModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Tạo đơn hàng mới từ giỏ hàng.
     *
     * Quy trình:
     * 1. Bắt đầu transaction
     * 2. INSERT vào bảng orders
     * 3. INSERT từng dòng vào order_details
     * 4. Commit nếu thành công, rollback nếu lỗi
     *
     * @param  string $name      Tên khách hàng
     * @param  string $phone     Số điện thoại
     * @param  string $address   Địa chỉ giao hàng
     * @param  array  $cartItems Giỏ hàng [productId => ['name', 'price', 'quantity', 'image']]
     * @return int    ID của đơn hàng vừa tạo
     * @throws Exception Nếu có lỗi trong quá trình xử lý
     */
    public function createOrder(string $name, string $phone, string $address, array $cartItems): int
    {
        $this->db->beginTransaction();

        try {
            // 1. Tạo đơn hàng
            $orderStmt = $this->db->prepare(
                "INSERT INTO orders (customer_name, phone, address) VALUES (:name, :phone, :address)"
            );
            $orderStmt->execute([
                ':name'    => trim($name),
                ':phone'   => trim($phone),
                ':address' => trim($address),
            ]);

            $orderId = (int)$this->db->lastInsertId();

            // 2. Tạo chi tiết đơn hàng
            $detailStmt = $this->db->prepare(
                "INSERT INTO order_details (order_id, product_id, quantity, price)
                 VALUES (:order_id, :product_id, :quantity, :price)"
            );

            foreach ($cartItems as $productId => $item) {
                $detailStmt->execute([
                    ':order_id'   => $orderId,
                    ':product_id' => (int)$productId,
                    ':quantity'   => (int)$item['quantity'],
                    ':price'      => (float)$item['price'],
                ]);
            }

            // 3. Commit
            $this->db->commit();

            return $orderId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
