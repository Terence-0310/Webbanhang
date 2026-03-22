<?php
/**
 * Database – Lớp quản lý kết nối CSDL (Singleton).
 *
 * Chỉ tạo duy nhất 1 kết nối PDO trong suốt vòng đời request,
 * tránh lãng phí tài nguyên khi nhiều Model cùng sử dụng.
 */
class Database
{
    /* ---------- Cấu hình ---------- */
    private const HOST     = '127.0.0.1';
    private const DB_NAME  = 'my_store';
    private const USERNAME = 'root';
    private const PASSWORD = '';
    private const CHARSET  = 'utf8mb4';

    /** @var PDO|null Instance duy nhất */
    private static ?PDO $instance = null;

    /**
     * Lấy kết nối PDO (tạo mới nếu chưa có).
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::HOST,
                self::DB_NAME,
                self::CHARSET
            );

            try {
                self::$instance = new PDO($dsn, self::USERNAME, self::PASSWORD, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                exit('Lỗi kết nối CSDL: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }

    /** Không cho phép tạo instance từ bên ngoài */
    private function __construct() {}
    private function __clone() {}
}
