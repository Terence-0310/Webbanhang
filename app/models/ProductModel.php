<?php
/**
 * ProductModel – Xử lý dữ liệu bảng `products`.
 *
 * Bao gồm: truy vấn danh sách, chi tiết, thêm, sửa, xóa sản phẩm.
 * Sử dụng Prepared Statements để chống SQL Injection.
 */

require_once __DIR__ . '/../config/database.php';

class ProductModel
{
    private PDO $db;
    private string $table = 'products';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /* ----------------------------------------------------------
     * READ – Lấy danh sách & chi tiết
     * ---------------------------------------------------------- */

    /**
     * Lấy tất cả sản phẩm (kèm tên danh mục).
     *
     * @return array Danh sách object sản phẩm
     */
    public function getAll(): array
    {
        $sql = "SELECT p.*, c.name AS category_name
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.id DESC";

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Lấy sản phẩm theo ID (kèm tên danh mục).
     *
     * @param  int         $id
     * @return object|false
     */
    public function getById(int $id)
    {
        $sql = "SELECT p.*, c.name AS category_name
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Lấy sản phẩm theo danh mục.
     *
     * @param  int   $categoryId
     * @return array
     */
    public function getByCategoryId(int $categoryId): array
    {
        $sql = "SELECT p.*, c.name AS category_name
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.category_id = :category_id
                ORDER BY p.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /* ----------------------------------------------------------
     * CREATE
     * ---------------------------------------------------------- */

    /**
     * Thêm sản phẩm mới.
     *
     * @return array|true  Mảng lỗi nếu validation thất bại, true nếu thành công
     */
    public function create(string $name, string $description, $price, ?int $categoryId, string $image = '')
    {
        $errors = $this->validate($name, $description, $price, $categoryId);
        if (!empty($errors)) {
            return $errors;
        }

        $sql = "INSERT INTO {$this->table} (name, description, price, category_id, image)
                VALUES (:name, :description, :price, :category_id, :image)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name'        => trim($name),
            ':description' => trim($description),
            ':price'       => (float)$price,
            ':category_id' => $categoryId,
            ':image'       => $image,
        ]);

        return true;
    }

    /* ----------------------------------------------------------
     * UPDATE
     * ---------------------------------------------------------- */

    /**
     * Cập nhật sản phẩm theo ID.
     *
     * @return array|true  Mảng lỗi nếu validation thất bại, true nếu thành công
     */
    public function update(int $id, string $name, string $description, $price, ?int $categoryId, string $image = '')
    {
        $errors = $this->validate($name, $description, $price, $categoryId);
        if (!empty($errors)) {
            return $errors;
        }

        $sql = "UPDATE {$this->table}
                SET name        = :name,
                    description = :description,
                    price       = :price,
                    category_id = :category_id,
                    image       = :image
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id'          => $id,
            ':name'        => trim($name),
            ':description' => trim($description),
            ':price'       => (float)$price,
            ':category_id' => $categoryId,
            ':image'       => $image,
        ]);

        return true;
    }

    /* ----------------------------------------------------------
     * DELETE
     * ---------------------------------------------------------- */

    /**
     * Xóa sản phẩm theo ID.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /* ----------------------------------------------------------
     * VALIDATION
     * ---------------------------------------------------------- */

    /**
     * Kiểm tra dữ liệu sản phẩm trước khi lưu.
     *
     * @return array Mảng các thông báo lỗi (rỗng nếu hợp lệ)
     */
    private function validate(string $name, string $description, $price, ?int $categoryId): array
    {
        $errors = [];

        if (trim($name) === '') {
            $errors[] = 'Tên sản phẩm không được để trống.';
        } elseif (mb_strlen(trim($name)) > 255) {
            $errors[] = 'Tên sản phẩm không được vượt quá 255 ký tự.';
        }

        if (trim($description) === '') {
            $errors[] = 'Mô tả sản phẩm không được để trống.';
        }

        if (!is_numeric($price) || (float)$price < 0) {
            $errors[] = 'Giá sản phẩm phải là số không âm.';
        }

        if (empty($categoryId)) {
            $errors[] = 'Vui lòng chọn danh mục cho sản phẩm.';
        }

        return $errors;
    }
}
