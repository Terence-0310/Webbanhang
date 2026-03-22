<?php
/**
 * CategoryController – Xử lý tất cả request liên quan đến danh mục.
 *
 * Các action:
 *  - index()        : Hiển thị danh sách danh mục
 *  - add()          : Form thêm danh mục mới
 *  - save()         : Xử lý lưu danh mục mới (POST)
 *  - edit($id)      : Form sửa danh mục
 *  - update($id)    : Xử lý cập nhật danh mục (POST)
 *  - delete($id)    : Xóa danh mục
 */

require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController
{
    private CategoryModel $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    /* ==========================================================
     * DANH SÁCH
     * ========================================================== */

    /**
     * Hiển thị danh sách tất cả danh mục.
     */
    public function index(): void
    {
        require_admin();
        $categories = $this->categoryModel->getAll();
        $this->render('category/list', [
            'pageTitle'  => 'Quản lý danh mục',
            'categories' => $categories,
        ]);
    }

    /* ==========================================================
     * THÊM DANH MỤC
     * ========================================================== */

    /**
     * Hiển thị form thêm danh mục mới.
     */
    public function add(): void
    {
        require_admin();
        $this->render('category/add', [
            'pageTitle' => 'Thêm danh mục mới',
            'errors'    => [],
        ]);
    }

    /**
     * Xử lý POST – Lưu danh mục mới.
     */
    public function save(): void
    {
        require_admin();
        $result = $this->categoryModel->create(
            $_POST['name'] ?? '',
            $_POST['description'] ?? ''
        );

        if (is_array($result)) {
            $this->render('category/add', [
                'pageTitle' => 'Thêm danh mục mới',
                'errors'    => $result,
            ]);
            return;
        }

        set_flash('success', 'Thêm danh mục thành công!');
        redirect('Category');
    }

    /* ==========================================================
     * SỬA DANH MỤC
     * ========================================================== */

    /**
     * Hiển thị form sửa danh mục.
     */
    public function edit(int $id): void
    {
        require_admin();
        $category = $this->findCategoryOrFail($id);
        $this->render('category/edit', [
            'pageTitle' => 'Sửa danh mục: ' . $category->name,
            'category'  => $category,
            'errors'    => [],
        ]);
    }

    /**
     * Xử lý POST – Cập nhật danh mục.
     */
    public function update(int $id): void
    {
        require_admin();
        $this->findCategoryOrFail($id);

        $result = $this->categoryModel->update(
            $id,
            $_POST['name'] ?? '',
            $_POST['description'] ?? ''
        );

        if (is_array($result)) {
            $category = (object)[
                'id'          => $id,
                'name'        => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
            ];
            $this->render('category/edit', [
                'pageTitle' => 'Sửa danh mục',
                'category'  => $category,
                'errors'    => $result,
            ]);
            return;
        }

        set_flash('success', 'Cập nhật danh mục thành công!');
        redirect('Category');
    }

    /* ==========================================================
     * XÓA DANH MỤC
     * ========================================================== */

    /**
     * Xóa danh mục theo ID.
     * Nếu danh mục còn chứa sản phẩm, sẽ hiển thị thông báo lỗi.
     */
    public function delete(int $id): void
    {
        require_admin();
        $this->findCategoryOrFail($id);

        $result = $this->categoryModel->delete($id);

        if (is_array($result)) {
            set_flash('danger', $result[0]);
        } else {
            set_flash('success', 'Đã xóa danh mục.');
        }

        redirect('Category');
    }

    /* ==========================================================
     * PRIVATE HELPERS
     * ========================================================== */

    /**
     * Tìm danh mục theo ID hoặc dừng chương trình nếu không tìm thấy.
     */
    private function findCategoryOrFail(int $id): object
    {
        $category = $this->categoryModel->getById($id);
        if (!$category) {
            http_response_code(404);
            exit('Không tìm thấy danh mục có ID: ' . $id);
        }
        return $category;
    }

    /**
     * Render view với dữ liệu.
     */
    private function render(string $view, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $view . '.php';
    }
}
