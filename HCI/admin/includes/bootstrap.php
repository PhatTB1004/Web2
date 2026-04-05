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

    echo '<nav><ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $query['page'] = $i;
        $class = $i === $page ? 'active' : '';
        echo '<li class="page-item ' . $class . '"><a class="page-link" href="?' . h(http_build_query($query)) . '">' . $i . '</a></li>';
    }
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

function update_order_status(int $orderId, string $newStatus): bool
{
    $conn = db();
    mysqli_begin_transaction($conn);

    try {
        $order = fetch_one('SELECT * FROM orders WHERE id = ' . (int) $orderId . ' FOR UPDATE');
        if (!$order) {
            throw new Exception('Không tìm thấy đơn hàng.');
        }

        $oldStatus = $order['status'];
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

        $stmt = mysqli_prepare($conn, 'UPDATE orders SET status = ? WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'si', $newStatus, $orderId);
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
?>
