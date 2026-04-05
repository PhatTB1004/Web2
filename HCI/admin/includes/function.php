<?php
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
