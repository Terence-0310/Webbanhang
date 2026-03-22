<?php
/**
 * AccountModel – Xử lý dữ liệu bảng `account`.
 *
 * Bao gồm: đăng ký, đăng nhập, lấy thông tin tài khoản.
 * Mật khẩu được mã hóa bằng password_hash() (bcrypt).
 */

require_once __DIR__ . '/../config/database.php';

class AccountModel
{
    private PDO $db;
    private string $table = 'account';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /* ----------------------------------------------------------
     * ĐĂNG KÝ (Register)
     * ---------------------------------------------------------- */

    /**
     * Tạo tài khoản mới.
     *
     * @param  string $username Tên đăng nhập
     * @param  string $fullname Họ tên đầy đủ
     * @param  string $password Mật khẩu (chưa mã hóa)
     * @param  string $confirmPassword Xác nhận mật khẩu
     * @return array|true Mảng lỗi hoặc true nếu thành công
     */
    public function register(string $username, string $fullname, string $password, string $confirmPassword)
    {
        $errors = $this->validateRegister($username, $fullname, $password, $confirmPassword);
        if (!empty($errors)) {
            return $errors;
        }

        // Mã hóa mật khẩu bằng bcrypt
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} (username, fullname, password, role) VALUES (:username, :fullname, :password, 'user')"
        );
        $stmt->execute([
            ':username' => trim($username),
            ':fullname' => trim($fullname),
            ':password' => $hashedPassword,
        ]);

        return true;
    }

    /* ----------------------------------------------------------
     * ĐĂNG NHẬP (Login)
     * ---------------------------------------------------------- */

    /**
     * Xác thực tài khoản đăng nhập.
     *
     * @param  string $username Tên đăng nhập
     * @param  string $password Mật khẩu (chưa mã hóa)
     * @return object|array   Object tài khoản nếu thành công, mảng lỗi nếu thất bại
     */
    public function login(string $username, string $password)
    {
        $errors = [];

        if (trim($username) === '') {
            $errors[] = 'Tên đăng nhập không được để trống.';
        }
        if (trim($password) === '') {
            $errors[] = 'Mật khẩu không được để trống.';
        }

        if (!empty($errors)) {
            return $errors;
        }

        // Tìm tài khoản theo username
        $account = $this->getByUsername(trim($username));

        if (!$account) {
            return ['Tên đăng nhập hoặc mật khẩu không đúng.'];
        }

        // Kiểm tra mật khẩu bằng password_verify
        if (!password_verify($password, $account->password)) {
            return ['Tên đăng nhập hoặc mật khẩu không đúng.'];
        }

        return $account;
    }

    /* ----------------------------------------------------------
     * TRUY VẤN
     * ---------------------------------------------------------- */

    /**
     * Lấy tài khoản theo username.
     *
     * @return object|false
     */
    public function getByUsername(string $username)
    {
        $stmt = $this->db->prepare(
            "SELECT id, username, fullname, password, role, created_at FROM {$this->table} WHERE username = :username"
        );
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Lấy tài khoản theo ID.
     *
     * @return object|false
     */
    public function getById(int $id)
    {
        $stmt = $this->db->prepare(
            "SELECT id, username, fullname, role, created_at FROM {$this->table} WHERE id = :id"
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /* ----------------------------------------------------------
     * VALIDATION
     * ---------------------------------------------------------- */

    /**
     * Kiểm tra dữ liệu đăng ký.
     */
    private function validateRegister(string $username, string $fullname, string $password, string $confirmPassword): array
    {
        $errors = [];
        $username = trim($username);
        $fullname = trim($fullname);

        // Username
        if ($username === '') {
            $errors[] = 'Tên đăng nhập không được để trống.';
        } elseif (mb_strlen($username) < 3) {
            $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự.';
        } elseif (mb_strlen($username) > 100) {
            $errors[] = 'Tên đăng nhập không được vượt quá 100 ký tự.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới.';
        } else {
            // Kiểm tra trùng username
            $existing = $this->getByUsername($username);
            if ($existing) {
                $errors[] = 'Tên đăng nhập "' . $username . '" đã được sử dụng.';
            }
        }

        // Fullname
        if ($fullname === '') {
            $errors[] = 'Họ tên không được để trống.';
        }

        // Password
        if ($password === '') {
            $errors[] = 'Mật khẩu không được để trống.';
        } elseif (mb_strlen($password) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
        }

        // Confirm password
        if ($password !== $confirmPassword) {
            $errors[] = 'Xác nhận mật khẩu không khớp.';
        }

        return $errors;
    }
}
