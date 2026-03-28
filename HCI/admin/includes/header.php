<?php
require_once __DIR__ . '/bootstrap.php';
if (empty($admin_public_page)) {
    require_admin();
}
$page_title = $page_title ?? 'Quản trị NHASACHTV';
$flash = consume_flash();
$currentAdmin = current_admin();
?>
<!doctype html>
<html lang="vi">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <title><?php echo h($page_title); ?></title>
   <link rel="shortcut icon" href="../images/favicon.ico" />
   <link rel="stylesheet" href="../css/bootstrap.min.css">
   <link rel="stylesheet" href="../css/typography.css">
   <link rel="stylesheet" href="../css/style.css">
   <link rel="stylesheet" href="../css/responsive.css">
   <link rel="stylesheet" href="../css/fontawesome.css">
   <link rel="stylesheet" href="../css/line-awesome.min.css">
   <link rel="stylesheet" href="../css/ionicons.min.css">
   <link rel="stylesheet" href="../css/remixicon.css">
</head>
<body>
<?php if ($flash): ?>
<div class="alert alert-<?php echo h($flash['type']); ?> mb-0 rounded-0 text-center"><?php echo h($flash['message']); ?></div>
<?php endif; ?>
