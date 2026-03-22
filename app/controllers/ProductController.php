<?php
/**
 * ProductController – Xử lý tất cả request liên quan đến sản phẩm.
 *
 * Các action:
 *  - index()             : Hiển thị danh sách sản phẩm
 *  - show($id)           : Hiển thị chi tiết sản phẩm
 *  - add()               : Form thêm sản phẩm mới
 *  - save()              : Xử lý lưu sản phẩm mới (POST)
 *  - edit($id)           : Form sửa sản phẩm
 *  - update($id)         : Xử lý cập nhật sản phẩm (POST)
 *  - delete($id)         : Xóa sản phẩm
 *  - addToCart($id)       : Thêm sản phẩm vào giỏ hàng
 *  - cart()              : Hiển thị giỏ hàng
 *  - removeFromCart($id) : Xóa sản phẩm khỏi giỏ
 *  - checkout()          : Trang thanh toán
 *  - processCheckout()   : Xử lý đặt hàng (POST)
 *  - orderConfirmation() : Trang xác nhận đơn hàng
 */

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/OrderModel.php';

class ProductController
{
    private ProductModel  $productModel;
    private CategoryModel $categoryModel;
    private OrderModel    $orderModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->orderModel    = new OrderModel();
    }

    /* ==========================================================
     * DANH SÁCH & CHI TIẾT
     * ========================================================== */

    /**
     * Hiển thị danh sách tất cả sản phẩm.
     */
    public function index(): void
    {
        $products = $this->productModel->getAll();
        $this->render('product/list', [
            'pageTitle' => 'Danh sách sản phẩm',
            'products'  => $products,
        ]);
    }

    /**
     * Hiển thị chi tiết một sản phẩm.
     */
    public function show(int $id): void
    {
        $product = $this->findProductOrFail($id);
        $this->render('product/show', [
            'pageTitle' => $product->name,
            'product'   => $product,
        ]);
    }

    /* ==========================================================
     * THÊM SẢN PHẨM
     * ========================================================== */

    /**
     * Hiển thị form thêm sản phẩm mới.
     */
    public function add(): void
    {
        require_admin();
        $this->render('product/add', [
            'pageTitle'  => 'Thêm sản phẩm mới',
            'categories' => $this->categoryModel->getAll(),
            'errors'     => [],
        ]);
    }

    /**
     * Xử lý POST – Lưu sản phẩm mới.
     */
    public function save(): void
    {
        require_admin();
        $categories = $this->categoryModel->getAll();
        $image = '';

        // Xử lý upload ảnh
        try {
            if (!empty($_FILES['image']['name'])) {
                $image = upload_image($_FILES['image']);
            }
        } catch (Throwable $e) {
            $this->render('product/add', [
                'pageTitle'  => 'Thêm sản phẩm mới',
                'categories' => $categories,
                'errors'     => [$e->getMessage()],
            ]);
            return;
        }

        // Lưu sản phẩm
        $result = $this->productModel->create(
            $_POST['name'] ?? '',
            $_POST['description'] ?? '',
            $_POST['price'] ?? '',
            !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            $image
        );

        if (is_array($result)) {
            $this->render('product/add', [
                'pageTitle'  => 'Thêm sản phẩm mới',
                'categories' => $categories,
                'errors'     => $result,
            ]);
            return;
        }

        set_flash('success', 'Thêm sản phẩm thành công!');
        redirect('Product');
    }

    /* ==========================================================
     * SỬA SẢN PHẨM
     * ========================================================== */

    /**
     * Hiển thị form sửa sản phẩm.
     */
    public function edit(int $id): void
    {
        require_admin();
        $product = $this->findProductOrFail($id);
        $this->render('product/edit', [
            'pageTitle'  => 'Sửa sản phẩm: ' . $product->name,
            'product'    => $product,
            'categories' => $this->categoryModel->getAll(),
            'errors'     => [],
        ]);
    }

    /**
     * Xử lý POST – Cập nhật sản phẩm.
     */
    public function update(int $id): void
    {
        require_admin();
        $product = $this->findProductOrFail($id);
        $categories = $this->categoryModel->getAll();
        $image = $_POST['existing_image'] ?? ($product->image ?? '');

        // Xử lý upload ảnh mới (nếu có)
        try {
            if (!empty($_FILES['image']['name'])) {
                $image = upload_image($_FILES['image']);
            }
        } catch (Throwable $e) {
            $this->renderEditWithErrors($id, $image, [$e->getMessage()], $categories);
            return;
        }

        // Cập nhật sản phẩm
        $result = $this->productModel->update(
            $id,
            $_POST['name'] ?? '',
            $_POST['description'] ?? '',
            $_POST['price'] ?? '',
            !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            $image
        );

        if (is_array($result)) {
            $this->renderEditWithErrors($id, $image, $result, $categories);
            return;
        }

        set_flash('success', 'Cập nhật sản phẩm thành công!');
        redirect('Product');
    }

    /* ==========================================================
     * XÓA SẢN PHẨM
     * ========================================================== */

    /**
     * Xóa sản phẩm theo ID.
     */
    public function delete(int $id): void
    {
        require_admin();
        $this->findProductOrFail($id);
        $this->productModel->delete($id);
        set_flash('success', 'Đã xóa sản phẩm.');
        redirect('Product');
    }

    /* ==========================================================
     * GIỎ HÀNG
     * ========================================================== */

    /**
     * Thêm sản phẩm vào giỏ hàng (Session).
     */
    public function addToCart(int $id): void
    {
        $product = $this->findProductOrFail($id);

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name'     => $product->name,
                'price'    => (float)$product->price,
                'quantity' => 1,
                'image'    => $product->image ?? '',
            ];
        }

        set_flash('success', 'Đã thêm "' . $product->name . '" vào giỏ hàng.');
        redirect('Product/cart');
    }

    /**
     * Hiển thị giỏ hàng.
     */
    public function cart(): void
    {
        $this->render('product/cart', [
            'pageTitle' => 'Giỏ hàng',
            'cart'      => $_SESSION['cart'] ?? [],
        ]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng.
     */
    public function removeFromCart(int $id): void
    {
        if (isset($_SESSION['cart'][$id])) {
            $name = $_SESSION['cart'][$id]['name'];
            unset($_SESSION['cart'][$id]);
            set_flash('info', 'Đã xóa "' . $name . '" khỏi giỏ hàng.');
        }
        redirect('Product/cart');
    }

    /* ==========================================================
     * THANH TOÁN
     * ========================================================== */

    /**
     * Hiển thị trang thanh toán.
     */
    public function checkout(): void
    {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            redirect('Product/cart');
            return;
        }

        $this->render('product/checkout', [
            'pageTitle' => 'Thanh toán',
            'cart'      => $cart,
            'errors'    => [],
        ]);
    }

    /**
     * Xử lý POST – Đặt hàng.
     */
    public function processCheckout(): void
    {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            redirect('Product/cart');
            return;
        }

        // Validation
        $name    = trim($_POST['name'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $errors  = [];

        if ($name === '') {
            $errors[] = 'Họ tên không được để trống.';
        }
        if ($phone === '') {
            $errors[] = 'Số điện thoại không được để trống.';
        } elseif (!preg_match('/^[0-9]{9,15}$/', $phone)) {
            $errors[] = 'Số điện thoại không hợp lệ (9-15 chữ số).';
        }
        if ($address === '') {
            $errors[] = 'Địa chỉ giao hàng không được để trống.';
        }

        if (!empty($errors)) {
            $this->render('product/checkout', [
                'pageTitle' => 'Thanh toán',
                'cart'      => $cart,
                'errors'    => $errors,
            ]);
            return;
        }

        // Tạo đơn hàng
        try {
            $this->orderModel->createOrder($name, $phone, $address, $cart);
            unset($_SESSION['cart']);
            redirect('Product/orderConfirmation');
        } catch (Throwable $e) {
            $this->render('product/checkout', [
                'pageTitle' => 'Thanh toán',
                'cart'      => $cart,
                'errors'    => ['Đặt hàng thất bại: ' . $e->getMessage()],
            ]);
        }
    }

    /**
     * Trang xác nhận đặt hàng thành công.
     */
    public function orderConfirmation(): void
    {
        $this->render('product/orderConfirmation', [
            'pageTitle' => 'Đặt hàng thành công',
        ]);
    }

    /* ==========================================================
     * PRIVATE HELPERS
     * ========================================================== */

    /**
     * Tìm sản phẩm theo ID hoặc dừng chương trình nếu không tìm thấy.
     */
    private function findProductOrFail(int $id): object
    {
        $product = $this->productModel->getById($id);
        if (!$product) {
            http_response_code(404);
            exit('Không tìm thấy sản phẩm có ID: ' . $id);
        }
        return $product;
    }

    /**
     * Render view với dữ liệu.
     * Extract $data thành các biến riêng lẻ rồi include file view.
     */
    private function render(string $view, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $view . '.php';
    }

    /**
     * Render lại form edit kèm lỗi (dùng khi validation thất bại).
     */
    private function renderEditWithErrors(int $id, string $image, array $errors, array $categories): void
    {
        $product = (object)[
            'id'          => $id,
            'name'        => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price'       => $_POST['price'] ?? '',
            'category_id' => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'image'       => $image,
        ];

        $this->render('product/edit', [
            'pageTitle'  => 'Sửa sản phẩm',
            'product'    => $product,
            'categories' => $categories,
            'errors'     => $errors,
        ]);
    }
}
