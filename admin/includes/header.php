<?php
$adminName = $_SESSION['admin_name'] ?? 'مدير';
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $adminPageTitle ?? 'لوحة التحكم' ?> - Land Scape Travel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/admin/includes/admin.css">
</head>
<body>

<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <span class="brand-land">Land</span><span class="brand-scape">Scape</span>
            <small>Admin</small>
        </div>
        <nav class="sidebar-nav">
            <a href="/admin/dashboard" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i> لوحة التحكم
            </a>
            <a href="/admin/programs" class="<?= $currentPage === 'programs' || $currentPage === 'program-edit' ? 'active' : '' ?>">
                <i class="fas fa-suitcase"></i> البرامج
            </a>
            <a href="/admin/categories" class="<?= $currentPage === 'categories' ? 'active' : '' ?>">
                <i class="fas fa-folder"></i> التصنيفات
            </a>
            <a href="/admin/bookings" class="<?= $currentPage === 'bookings' ? 'active' : '' ?>">
                <i class="fas fa-calendar-check"></i> الحجوزات
            </a>
            <a href="/admin/messages" class="<?= $currentPage === 'messages' ? 'active' : '' ?>">
                <i class="fas fa-envelope"></i> الرسائل
            </a>
            <a href="/admin/articles" class="<?= $currentPage === 'articles' || $currentPage === 'article-edit' ? 'active' : '' ?>">
                <i class="fas fa-newspaper"></i> المدونة
            </a>
            <a href="/admin/testimonials" class="<?= $currentPage === 'testimonials' ? 'active' : '' ?>">
                <i class="fas fa-star"></i> الشهادات
            </a>
            <a href="/admin/banners" class="<?= $currentPage === 'banners' ? 'active' : '' ?>">
                <i class="fas fa-images"></i> البانرات
            </a>
            <a href="/admin/pages" class="<?= $currentPage === 'pages' || $currentPage === 'page-edit' ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i> الصفحات
            </a>
            <a href="/admin/menus" class="<?= $currentPage === 'menus' ? 'active' : '' ?>">
                <i class="fas fa-bars"></i> القوائم
            </a>
            <a href="/admin/urls" class="<?= $currentPage === 'urls' ? 'active' : '' ?>">
                <i class="fas fa-link"></i> الروابط
            </a>
            <a href="/admin/colors" class="<?= $currentPage === 'colors' ? 'active' : '' ?>">
                <i class="fas fa-palette"></i> الألوان
            </a>
            <a href="/admin/settings" class="<?= $currentPage === 'settings' ? 'active' : '' ?>">
                <i class="fas fa-cog"></i> الإعدادات
            </a>
            <a href="/admin/users" class="<?= $currentPage === 'users' ? 'active' : '' ?>">
                <i class="fas fa-users-cog"></i> المستخدمين
            </a>
            <hr style="border-color: rgba(255,255,255,0.1);">
            <a href="/" target="_blank">
                <i class="fas fa-external-link-alt"></i> عرض الموقع
            </a>
            <a href="/admin/logout" class="text-danger">
                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Top Bar -->
        <div class="admin-topbar">
            <button class="btn btn-link sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="topbar-right">
                <span class="admin-name">
                    <i class="fas fa-user-circle me-1"></i> <?= clean($adminName) ?>
                </span>
            </div>
        </div>

        <!-- Content -->
        <div class="admin-content">
