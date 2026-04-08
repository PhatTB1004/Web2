<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_admin();

$id = (int) ($_GET['id'] ?? 0);

$book = fetch_one("SELECT status FROM books WHERE id = {$id}");
if ($book) {
    $newStatus = $book['status'] === 'hidden' ? 'active' : 'hidden';
    mysqli_query(db(), "UPDATE books SET status = '{$newStatus}', updated_at = NOW() WHERE id = {$id}");
}

redirect('books.php');