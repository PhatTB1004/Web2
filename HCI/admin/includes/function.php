<?php
function upload_file($field_name, $target_dir, $old_file = null) {
    if (!isset($_FILES[$field_name]) || $_FILES[$field_name]['error'] !== 0) {
        return null;
    }

    $file = $_FILES[$field_name];

    // Tạo thư mục nếu chưa có
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $ext;
    $target_path = rtrim($target_dir, '/') . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Xóa file cũ nếu có
        if ($old_file && file_exists($old_file)) {
            unlink($old_file);
        }

        return str_replace('../', '', $target_path); // lưu DB dạng images/...
    }

    return null;
}

function delete_file_if_exists($filepath) {
    if (!$filepath) return;

    $fullPath = '../' . ltrim($filepath, '/');
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
}
?>