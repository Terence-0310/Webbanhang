<?php
/**
 * AccountController – Xử lý đăng nhập, đăng ký, đăng xuất.
 *
 * Các action:
 *  - login()           : Hiển thị form đăng nhập
 *  - processLogin()    : Xử lý POST đăng nhập
 *  - register()        : Hiển thị form đăng ký
 *  - processRegister() : Xử lý POST đăng ký
 *  - logout()          : Đăng xuất (xóa session)
 */

require_once __DIR__ . '/../models/AccountModel.php';

class AccountController
{
    private AccountModel $accountModel;

    public function __construct()
    {
        $this->accountModel = new AccountModel();
    }

    /* ==========================================================
     * ĐĂNG NHẬP
     * ========================================================== */

    /**
     * Hiển thị form đăng nhập.
     */
    public function login(): void
    {
        // Nếu đã đăng nhập → về trang chủ
        if (isLoggedIn()) {
            redirect('Product');
            return;
        }

        $this->render('account/login', [
            'pageTitle' => 'Đăng nhập',
            'errors'    => [],
        ]);
    }

    /**
     * Xử lý POST – Đăng nhập.
     */
    public function processLogin(): void
    {
        if (isLoggedIn()) {
            redirect('Product');
            return;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->accountModel->login($username, $password);

        // Nếu validation thất bại (trả về mảng lỗi)
        if (is_array($result)) {
            $this->render('account/login', [
                'pageTitle' => 'Đăng nhập',
                'errors'    => $result,
            ]);
            return;
        }

        // Đăng nhập thành công – Lưu thông tin vào session
        $_SESSION['user_id']       = $result->id;
        $_SESSION['user_username'] = $result->username;
        $_SESSION['user_fullname'] = $result->fullname;
        $_SESSION['user_role']     = $result->role;

        set_flash('success', 'Chào mừng ' . $result->fullname . '!');
        redirect('Product');
    }

    /* ==========================================================
     * ĐĂNG KÝ
     * ========================================================== */

    /**
     * Hiển thị form đăng ký.
     */
    public function register(): void
    {
        if (isLoggedIn()) {
            redirect('Product');
            return;
        }

        $this->render('account/register', [
            'pageTitle' => 'Đăng ký tài khoản',
            'errors'    => [],
        ]);
    }

    /**
     * Xử lý POST – Đăng ký tài khoản mới.
     */
    public function processRegister(): void
    {
        if (isLoggedIn()) {
            redirect('Product');
            return;
        }

        $username        = $_POST['username']         ?? '';
        $fullname        = $_POST['fullname']         ?? '';
        $password        = $_POST['password']         ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $result = $this->accountModel->register($username, $fullname, $password, $confirmPassword);

        if (is_array($result)) {
            $this->render('account/register', [
                'pageTitle' => 'Đăng ký tài khoản',
                'errors'    => $result,
            ]);
            return;
        }

        set_flash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
        redirect('Account/login');
    }

    /* ==========================================================
     * ĐĂNG XUẤT
     * ========================================================== */

    /**
     * Đăng xuất – Xóa session và redirect về trang chủ.
     */
    public function logout(): void
    {
        // Xóa toàn bộ dữ liệu session liên quan đến user
        unset(
            $_SESSION['user_id'],
            $_SESSION['user_username'],
            $_SESSION['user_fullname'],
            $_SESSION['user_role']
        );

        set_flash('info', 'Bạn đã đăng xuất thành công.');
        redirect('Account/login');
    }

    /* ==========================================================
     * PRIVATE HELPERS
     * ========================================================== */

    /**
     * Render view với dữ liệu.
     */
    private function render(string $view, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../views/' . $view . '.php';
    }
}
