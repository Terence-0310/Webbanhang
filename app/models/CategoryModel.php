<?php
/**
 * CategoryModel – Xử lý dữ liệu bảng `categories`.
 *
 * Bao gồm: truy vấn danh sách, chi tiết, thêm, sửa, xóa danh mục.
 */

require_once __DIR__ . '/../config/database.php';

class CategoryModel
{
    private PDO $db;
    private string $table = 'categories';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /* ----------------------------------------------------------
     * READ
     * ---------------------------------------------------------- */

    /**
     * Lấy tất cả danh mục, sắp xếp theo ID giảm dần.
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id ASC";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Lấy danh mục theo ID.
     *
     * @return object|false
     */
    public function getById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Đếm số sản phẩm thuộc danh mục.
     */
    public function countProducts(int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /* ----------------------------------------------------------
     * CREATE
     * ---------------------------------------------------------- */

    /**
     * Thêm danh mục mới.
     *
     * @return array|true  Mảng lỗi hoặc true nếu thành công
     */
    public function create(string $name, string $description = '')
    {
        $errors = $this->validate($name);
        if (!empty($errors)) {
            return $errors;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (name, description) VALUES (:name, :description)"
        );
        $stmt->execute([
            ':name'        => trim($name),
            ':description' => trim($description),
        ]);

        return true;
    }

    /* ----------------------------------------------------------
     * UPDATE
     * ---------------------------------------------------------- */

    /**
     * Cập nhật danh mục theo ID.
     *
     * @return array|true
     */
    public function update(int $id, string $name, string $description = '')
    {
        $errors = $this->validate($name, $id);
        if (!empty($errors)) {
            return $errors;
        }

        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET name = :name, description = :description WHERE id = :id"
        );
        $stmt->execute([
            ':id'          => $id,
            ':name'        => trim($name),
            ':description' => trim($description),
        ]);

        return true;
    }

    /* ----------------------------------------------------------
     * DELETE
     * ---------------------------------------------------------- */

    /**
     * Xóa danh mục theo ID.
     * Sẽ trả về lỗi nếu danh mục còn chứa sản phẩm.
     *
     * @return array|true
     */
    public function delete(int $id)
    {
        // Kiểm tra xem danh mục có sản phẩm không
        $productCount = $this->countProducts($id);
        if ($productCount > 0) {
            return ["Không thể xóa danh mục này vì còn {$productCount} sản phẩm đang sử dụng."];
        }

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return true;
    }

    /* ----------------------------------------------------------
     * VALIDATION
     * ---------------------------------------------------------- */

    /**
     * Kiểm tra dữ liệu danh mục.
     *
     * @param string   $name Tên danh mục
     * @param int|null $excludeId ID để loại trừ khi kiểm tra trùng tên (dùng khi update)
     */
    private function validate(string $name, ?int $excludeId = null): array
    {
        $errors = [];
        $name = trim($name);

        if ($name === '') {
            $errors[] = 'Tên danh mục không được để trống.';
        } elseif (mb_strlen($name) > 255) {
            $errors[] = 'Tên danh mục không được vượt quá 255 ký tự.';
        }

        // Kiểm tra trùng tên
        if ($name !== '' && empty($errors)) {
            $sql = "SELECT id FROM {$this->table} WHERE name = :name";
            $params = [':name' => $name];

            if ($excludeId !== null) {
                $sql .= " AND id != :exclude_id";
                $params[':exclude_id'] = $excludeId;
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            if ($stmt->fetch()) {
                $errors[] = 'Tên danh mục "' . $name . '" đã tồn tại.';
            }
        }

        return $errors;
    }
}
