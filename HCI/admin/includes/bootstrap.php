<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'nhasach';

if (!isset($conn) || !$conn instanceof mysqli) {
    $conn = mysqli_connect($host, $user, $pass, $dbName);
    if (!$conn) {
        die('Kết nối CSDL thất bại.');
    }
    mysqli_set_charset($conn, 'utf8mb4');
}

function db(): mysqli
{
    global $conn;
    return $conn;
}

function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function vn_money($value): string
{
    return number_format((float) $value, 0, ',', '.');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function consume_flash(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function fetch_one(string $sql): ?array
{
    $result = mysqli_query(db(), $sql);
    if (!$result) {
        return null;
    }
    $row = mysqli_fetch_assoc($result);
    return $row ?: null;
}

function fetch_all(string $sql): array
{
    $result = mysqli_query(db(), $sql);
    if (!$result) {
        return [];
    }
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function fetch_count(string $sql): int
{
    $row = fetch_one($sql);
    if (!$row) {
        return 0;
    }
    $values = array_values($row);
    return isset($values[0]) ? (int) $values[0] : 0;
}

function next_code(string $table, string $prefix, string $column = 'code', int $pad = 3): string
{
    $prefixEsc = mysqli_real_escape_string(db(), $prefix);
    $sql = "SELECT {$column} FROM {$table} WHERE {$column} LIKE '{$prefixEsc}%' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query(db(), $sql);
    $number = 0;
    if ($result && ($row = mysqli_fetch_assoc($result))) {
        if (preg_match('/(\d+)$/', $row[$column], $m)) {
            $number = (int) $m[1];
        }
    }
    return $prefix . str_pad((string) ($number + 1), $pad, '0', STR_PAD_LEFT);
}

function calc_sale_price(float $cost, float $profitPercent): float
{
    return round($cost * (1 + $profitPercent / 100), 2);
}

function app_settings_file(): string
{
    return dirname(__DIR__, 2) . '/app-settings.json';
}

function app_settings_defaults(): array
{
    return [
        'default_profit_percent' => 20,
        'inventory_threshold' => 5,
    ];
}

function app_settings(): array
{
    static $settings = null;
    if ($settings !== null) {
        return $settings;
    }

    $settings = app_settings_defaults();
    $file = app_settings_file();
    if (is_file($file)) {
        $json = json_decode((string) file_get_contents($file), true);
        if (is_array($json)) {
            $settings = array_merge($settings, $json);
        }
    }

    return $settings;
}

function app_setting(string $key, $default = null)
{
    $settings = app_settings();
    return $settings[$key] ?? $default;
}

function save_app_setting(string $key, $value): bool
{
    $file = app_settings_file();
    $settings = app_settings();
    $settings[$key] = is_numeric($value) ? (float) $value : $value;

    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    return (bool) file_put_contents($file, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function list_sort_state(array $allowed, string $defaultField, string $defaultDir = 'asc'): array
{
    $sort = strtolower((string) ($_GET['sort'] ?? $defaultField));
    if (!array_key_exists($sort, $allowed)) {
        $sort = $defaultField;
    }

    $dir = strtolower((string) ($_GET['dir'] ?? $defaultDir));
    if (!in_array($dir, ['asc', 'desc'], true)) {
        $dir = $defaultDir;
    }

    return [$sort, $dir];
}

function list_sort_clause(array $allowed, string $sort, string $dir, string $defaultField): string
{
    $column = $allowed[$sort] ?? $allowed[$defaultField] ?? $defaultField;
    $direction = strtolower($dir) === 'desc' ? 'DESC' : 'ASC';
    return 'ORDER BY ' . $column . ' ' . $direction;
}

function sort_url(string $field, array $query, string $currentSort, string $currentDir): string
{
    $query['sort'] = $field;
    $query['dir'] = ($currentSort === $field && strtolower($currentDir) === 'asc') ? 'desc' : 'asc';
    return '?' . http_build_query($query);
}

function render_sortable_th(string $field, string $label, array $query, string $currentSort, string $currentDir): string
{
    $active = $currentSort === $field;
    $arrow = $active ? ($currentDir === 'asc' ? ' ↑' : ' ↓') : '';
    $url = sort_url($field, $query, $currentSort, $currentDir);
    $class = $active ? ' class="text-primary"' : '';
    return '<a' . $class . ' href="' . h($url) . '">' . h($label) . $arrow . '</a>';
}

function order_status_allowed_values(string $status): array
{
    switch ($status) {
        case 'pending':
            return ['pending', 'confirmed', 'cancelled'];
        case 'confirmed':
            return ['confirmed', 'delivered', 'cancelled'];
        case 'delivered':
            return ['delivered'];
        case 'cancelled':
            return ['cancelled'];
        default:
            return ['pending', 'confirmed', 'delivered', 'cancelled'];
    }
}

function order_status_select_options(string $currentStatus): string
{
    $labels = [
        'pending' => 'Chờ xử lý',
        'confirmed' => 'Đã xác nhận',
        'delivered' => 'Đã giao',
        'cancelled' => 'Đã hủy',
    ];
    $allowed = order_status_allowed_values($currentStatus);
    $html = '';
    foreach ($allowed as $status) {
        $html .= '<option value="' . h($status) . '"' . ($currentStatus === $status ? ' selected' : '') . '>' . h($labels[$status] ?? $status) . '</option>';
    }
    return $html;
}

function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin']) && is_array($_SESSION['admin']);
}

function current_admin(): ?array
{
    return $_SESSION['admin'] ?? null;
}

function require_admin(): void
{
    if (!is_admin_logged_in()) {
        redirect('sign-in.php');
    }
}

function user_status_badge(string $status): string
{
    return $status === 'locked' ? 'badge badge-danger' : 'badge badge-success';
}

function book_status_badge(string $status): string
{
    return $status === 'hidden' ? 'badge badge-secondary' : 'badge badge-success';
}

function order_status_badge(string $status): string
{
    switch ($status) {
        case 'confirmed':
            return 'badge badge-info';
        case 'delivered':
            return 'badge badge-success';
        case 'cancelled':
            return 'badge badge-danger';
        default:
            return 'badge badge-warning';
    }
}

function import_status_badge(string $status): string
{
    return $status === 'completed' ? 'badge badge-success' : 'badge badge-warning';
}

function render_pagination(int $page, int $totalPages, array $query = []): void
{
    if ($totalPages <= 1) {
        return;
    }

    echo '<nav><ul class="pagination justify-content-center flex-wrap">';

    $prevDisabled = $page <= 1 ? ' disabled' : '';
    $query['page'] = max(1, $page - 1);
    echo '<li class="page-item' . $prevDisabled . '"><a class="page-link" href="?' . h(http_build_query($query)) . '">‹‹</a></li>';

    for ($i = 1; $i <= $totalPages; $i++) {
        $query['page'] = $i;
        $class = $i === $page ? ' active' : '';
        echo '<li class="page-item' . $class . '"><a class="page-link" href="?' . h(http_build_query($query)) . '">' . $i . '</a></li>';
    }

    $nextDisabled = $page >= $totalPages ? ' disabled' : '';
    $query['page'] = min($totalPages, $page + 1);
    echo '<li class="page-item' . $nextDisabled . '"><a class="page-link" href="?' . h(http_build_query($query)) . '">››</a></li>';
    echo '</ul></nav>';
}

function save_book_categories(int $bookId, array $categoryIds): void
{
    $conn = db();
    mysqli_query($conn, 'DELETE FROM book_category WHERE book_id = ' . (int) $bookId);
    $categoryIds = array_values(array_unique(array_filter(array_map('intval', $categoryIds))));
    if (!$categoryIds) {
        return;
    }
    $stmt = mysqli_prepare($conn, 'INSERT INTO book_category (book_id, category_id) VALUES (?, ?)');
    foreach ($categoryIds as $categoryId) {
        mysqli_stmt_bind_param($stmt, 'ii', $bookId, $categoryId);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
}

function get_book_category_ids(int $bookId): array
{
    $rows = fetch_all('SELECT category_id FROM book_category WHERE book_id = ' . (int) $bookId . ' ORDER BY category_id');
    return array_map(function ($row) { return (int) $row['category_id']; }, $rows);
}

function recalc_book_sell_price(int $bookId): void
{
    $book = fetch_one('SELECT cost_price, profit_percent FROM books WHERE id = ' . (int) $bookId);
    if (!$book) {
        return;
    }
    $sell = calc_sale_price((float) $book['cost_price'], (float) $book['profit_percent']);
    $stmt = mysqli_prepare(db(), 'UPDATE books SET sell_price = ?, updated_at = NOW() WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'di', $sell, $bookId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function create_completed_import_entry(int $bookId, int $quantity, float $importPrice, string $note = 'Nhập ban đầu khi thêm sản phẩm'): int
{
    $date = date('Y-m-d');
    $completedAt = date('Y-m-d H:i:s');
    $status = 'completed';
    $total = $quantity * $importPrice;

    $stmt = mysqli_prepare(db(), 'INSERT INTO imports (`date`, status, note, total_amount, completed_at) VALUES (?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sssds', $date, $status, $note, $total, $completedAt);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $importId = (int) mysqli_insert_id(db());

    $subtotal = $total;
    $stmt = mysqli_prepare(db(), 'INSERT INTO import_items (import_id, book_id, import_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'iidid', $importId, $bookId, $importPrice, $quantity, $subtotal);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $importId;
}

function complete_import(int $importId): bool
{
    $conn = db();
    mysqli_begin_transaction($conn);

    try {
        $import = fetch_one('SELECT * FROM imports WHERE id = ' . (int) $importId . ' FOR UPDATE');
        if (!$import) {
            throw new Exception('Không tìm thấy phiếu nhập.');
        }
        if ($import['status'] === 'completed') {
            throw new Exception('Phiếu nhập đã hoàn thành.');
        }

        $items = fetch_all('SELECT ii.*, b.stock_quantity, b.cost_price, b.profit_percent FROM import_items ii JOIN books b ON b.id = ii.book_id WHERE ii.import_id = ' . (int) $importId . ' FOR UPDATE');
        $total = 0.0;

        foreach ($items as $item) {
            $bookId = (int) $item['book_id'];
            $currentStock = (float) $item['stock_quantity'];
            $currentCost = (float) $item['cost_price'];
            $qty = (float) $item['quantity'];
            $importPrice = (float) $item['import_price'];
            $newStock = $currentStock + $qty;
            $newCost = $newStock > 0 ? (($currentStock * $currentCost) + ($qty * $importPrice)) / $newStock : $importPrice;
            $profit = (float) $item['profit_percent'];
            $newSell = calc_sale_price($newCost, $profit);
            $subtotal = $qty * $importPrice;
            $total += $subtotal;

            $stmt = mysqli_prepare($conn, 'UPDATE books SET stock_quantity = ?, cost_price = ?, sell_price = ?, updated_at = NOW() WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'dddi', $newStock, $newCost, $newSell, $bookId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $stmt = mysqli_prepare($conn, 'UPDATE import_items SET subtotal = ? WHERE id = ?');
            $itemId = (int) $item['id'];
            mysqli_stmt_bind_param($stmt, 'di', $subtotal, $itemId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        $stmt = mysqli_prepare($conn, 'UPDATE imports SET status = ?, completed_at = NOW(), total_amount = ? WHERE id = ?');
        $status = 'completed';
        mysqli_stmt_bind_param($stmt, 'sdi', $status, $total, $importId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        return true;
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        flash('danger', $e->getMessage());
        return false;
    }
}

function update_order_status(int $orderId, string $newStatus, string $cancelReason = '', string $cancelBy = ''): bool
{
    $conn = db();
    mysqli_begin_transaction($conn);

    try {
        $order = fetch_one('SELECT * FROM orders WHERE id = ' . (int) $orderId . ' FOR UPDATE');
        if (!$order) {
            throw new Exception('Không tìm thấy đơn hàng.');
        }

        $oldStatus = $order['status'];
        $allowed = order_status_allowed_values($oldStatus);
        if (!in_array($newStatus, $allowed, true)) {
            throw new Exception('Trạng thái đơn hàng không hợp lệ.');
        }

        if ($oldStatus !== $newStatus) {
            $items = fetch_all('SELECT oi.book_id, oi.quantity, b.stock_quantity, b.bookname FROM order_items oi JOIN books b ON b.id = oi.book_id WHERE oi.order_id = ' . (int) $orderId . ' FOR UPDATE');

            if ($oldStatus !== 'delivered' && $newStatus === 'delivered') {
                foreach ($items as $item) {
                    $qty = (float) $item['quantity'];
                    $stock = (float) $item['stock_quantity'];
                    if ($stock < $qty) {
                        throw new Exception('Sản phẩm ' . ($item['bookname'] ?? '') . ' không đủ tồn kho để giao.');
                    }
                }
                foreach ($items as $item) {
                    $stmt = mysqli_prepare($conn, 'UPDATE books SET stock_quantity = stock_quantity - ?, updated_at = NOW() WHERE id = ?');
                    $qty = (float) $item['quantity'];
                    $bookId = (int) $item['book_id'];
                    mysqli_stmt_bind_param($stmt, 'di', $qty, $bookId);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
            } elseif ($oldStatus === 'delivered' && $newStatus !== 'delivered') {
                foreach ($items as $item) {
                    $stmt = mysqli_prepare($conn, 'UPDATE books SET stock_quantity = stock_quantity + ?, updated_at = NOW() WHERE id = ?');
                    $qty = (float) $item['quantity'];
                    $bookId = (int) $item['book_id'];
                    mysqli_stmt_bind_param($stmt, 'di', $qty, $bookId);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
            }
        }

        if ($newStatus === 'delivered') {
            $today = date('Y-m-d');
            $stmt = mysqli_prepare($conn, 'UPDATE orders SET status = ?, delivery_date = ?, date_received = ?, cancel_reason = NULL, cancel_by = NULL WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'sssi', $newStatus, $today, $today, $orderId);
        } elseif ($newStatus === 'cancelled') {
    $stmt = mysqli_prepare($conn, '
        UPDATE orders 
        SET status = ?, 
            cancel_reason = ?, 
            cancel_by = ?,
            date_received = NULL,
            delivery_date = NULL
        WHERE id = ?
    ');
    mysqli_stmt_bind_param($stmt, 'sssi', $newStatus, $cancelReason, $cancelBy, $orderId);
} else {
    $stmt = mysqli_prepare($conn, '
        UPDATE orders 
        SET status = ?, 
            date_received = NULL,
            delivery_date = NULL,
            cancel_reason = NULL, 
            cancel_by = NULL 
        WHERE id = ?
    ');
    mysqli_stmt_bind_param($stmt, 'si', $newStatus, $orderId);
}

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);
        return true;
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        flash('danger', $e->getMessage());
        return false;
    }
}

function book_categories_text(int $bookId): string
{
    $rows = fetch_all('SELECT c.name FROM book_category bc JOIN categories c ON c.id = bc.category_id WHERE bc.book_id = ' . (int) $bookId . ' ORDER BY c.name');
    return implode(', ', array_map(function ($r) { return $r['name']; }, $rows));
}

function selected_attr(bool $cond): string
{
    return $cond ? 'selected' : '';
}
function delete_import(int $importId): bool
{
    $conn = db();
    mysqli_begin_transaction($conn);

    try {
        $import = fetch_one('SELECT * FROM imports WHERE id = ' . (int) $importId . ' FOR UPDATE');
        if (!$import) {
            throw new Exception('Không tìm thấy phiếu nhập.');
        }

        if ($import['status'] === 'completed') {
            throw new Exception('Phiếu nhập đã hoàn thành nên không thể xóa.');
        }

        mysqli_query($conn, 'DELETE FROM import_items WHERE import_id = ' . (int) $importId);
        mysqli_query($conn, 'DELETE FROM imports WHERE id = ' . (int) $importId);

        mysqli_commit($conn);
        return true;
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        flash('danger', $e->getMessage());
        return false;
    }
}
function order_status_text($status) {
    $map = [
        'pending'   => 'Chờ xử lý',
        'confirmed' => 'Đã xác nhận',
        'delivered' => 'Đã giao',
        'cancelled' => 'Đã hủy',
    ];
    return $map[$status] ?? $status;
}
function book_status_text($status) {
    $map = [
        'active' => 'Hiển thị',
        'hidden' => 'Ẩn',
    ];
    return $map[$status] ?? $status;
}
function import_status_text($status) {
    switch ($status) {
        case 'draft':
            return 'Nháp';
        case 'completed':
            return 'Hoàn thành';
        default:
            return ucfirst($status);
    }
}
function upload_file($field_name, $target_dir, $old_file = null) {
    if (!isset($_FILES[$field_name]) || $_FILES[$field_name]['error'] !== 0) {
        return null;
    }

    $file = $_FILES[$field_name];

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $ext;
    $target_path = rtrim($target_dir, '/') . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        if ($old_file && file_exists($old_file)) {
            unlink($old_file);
        }

        return str_replace('../', '', $target_path);
    }

    return null;
}

function delete_file_if_exists($filepath, $filename = null) {
    if ($filepath === null && $filename === null) {
        return;
    }

    if ($filename === null) {
        $fullPath = '../' . ltrim((string) $filepath, '/');
    } else {
        $base = rtrim((string) $filepath, '/');
        $fullPath = $base . '/' . ltrim((string) $filename, '/');
    }

    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
}
?>
