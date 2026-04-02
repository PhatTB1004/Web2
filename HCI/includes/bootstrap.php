<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = mysqli_connect('localhost', 'root', '', 'nhasach');
if (!$conn) {
    die('Kết nối thất bại');
}
mysqli_set_charset($conn, 'utf8mb4');

function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function money_vn($value): string
{
    return number_format((float) $value, 0, ',', '.') . ' ₫';
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function current_user(?mysqli $conn = null): ?array
{
    if (!is_logged_in()) {
        return null;
    }

    if (isset($_SESSION['user_cache']) && is_array($_SESSION['user_cache'])) {
        return $_SESSION['user_cache'];
    }

    if (!$conn) {
        return [
            'id' => (int) $_SESSION['user_id'],
            'username' => $_SESSION['username'] ?? '',
            'fullname' => $_SESSION['fullname'] ?? '',
            'email' => $_SESSION['email'] ?? '',
            'phone' => $_SESSION['phone'] ?? '',
            'role' => $_SESSION['role'] ?? 'customer',
            'status' => $_SESSION['status'] ?? 'active',
        ];
    }

    $stmt = mysqli_prepare($conn, 'SELECT id, username, fullname, phone, email, role, status FROM users WHERE id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    if ($user) {
        $_SESSION['user_cache'] = $user;
    }

    return $user ?: null;
}

function require_login(string $redirect = 'sign-in.php'): void
{
    if (!is_logged_in()) {
        header('Location: ' . $redirect . '?next=' . urlencode($_SERVER['REQUEST_URI'] ?? 'index.php'));
        exit;
    }
}

function public_book_status_sql(): string
{
    return "(status = 'active' OR status = 'visible')";
}

function fetch_book_categories(mysqli $conn, int $book_id): array
{
    $sql = 'SELECT c.id, c.name FROM categories c INNER JOIN book_category bc ON bc.category_id = c.id WHERE bc.book_id = ? ORDER BY c.name';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $book_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    mysqli_stmt_close($stmt);
    return $rows;
}

function primary_category_name(array $categories): string
{
    return $categories[0]['name'] ?? 'Sách';
}

function book_url(int $id): string
{
    return 'book-page.php?id=' . $id;
}

function image_path(string $path): string
{
    return h($path ?: 'images/logo.png');
}

function get_int(string $key, int $default = 0): int
{
    $value = filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT);
    return $value !== null && $value !== false ? (int) $value : $default;
}

function get_string(string $key, string $default = ''): string
{
    $value = filter_input(INPUT_GET, $key, FILTER_UNSAFE_RAW);
    if ($value === null || $value === false) {
        return $default;
    }
    return trim((string) $value);
}

function book_cover_url(array $book): string
{
    return !empty($book['image']) ? h($book['image']) : 'images/logo.png';
}
