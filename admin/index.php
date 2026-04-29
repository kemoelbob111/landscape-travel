<?php
/**
 * Admin Panel Router
 */
if (!defined('PAGE_LOADED')) {
    define('PAGE_LOADED', true);
    require_once __DIR__ . '/../config/config.php';
}

$adminUri = $_SERVER['REQUEST_URI'];
$adminPath = parse_url($adminUri, PHP_URL_PATH);
$adminPath = str_replace('/admin', '', $adminPath);
$adminPath = rtrim($adminPath, '/');

if (empty($adminPath)) {
    $adminPath = '/';
}

// Check login for all pages except login
if ($adminPath !== '/login' && !isAdminLoggedIn()) {
    redirect('/admin/login');
}

switch ($adminPath) {
    case '/login':
        require __DIR__ . '/login.php';
        break;
    case '/logout':
        session_destroy();
        redirect('/admin/login');
        break;
    case '/':
    case '/dashboard':
        require __DIR__ . '/dashboard.php';
        break;
    case '/settings':
        require __DIR__ . '/settings.php';
        break;
    case '/programs':
        require __DIR__ . '/programs.php';
        break;
    case '/program-edit':
        require __DIR__ . '/program-edit.php';
        break;
    case '/categories':
        require __DIR__ . '/categories.php';
        break;
    case '/bookings':
        require __DIR__ . '/bookings.php';
        break;
    case '/messages':
        require __DIR__ . '/messages.php';
        break;
    case '/articles':
        require __DIR__ . '/articles.php';
        break;
    case '/article-edit':
        require __DIR__ . '/article-edit.php';
        break;
    case '/testimonials':
        require __DIR__ . '/testimonials.php';
        break;
    case '/banners':
        require __DIR__ . '/banners.php';
        break;
    case '/menus':
        require __DIR__ . '/menus.php';
        break;
    case '/pages':
        require __DIR__ . '/pages.php';
        break;
    case '/page-edit':
        require __DIR__ . '/page-edit.php';
        break;
    case '/users':
        require __DIR__ . '/users.php';
        break;
    case '/urls':
        require __DIR__ . '/urls.php';
        break;
    case '/colors':
        require __DIR__ . '/colors.php';
        break;
    default:
        redirect('/admin/dashboard');
        break;
}
