<?php
if (!defined('PAGE_LOADED')) {
    define('PAGE_LOADED', true);
}
require_once __DIR__ . '/../config/config.php';

$siteName = getSetting('site_name', 'Land Scape Travel');
$primaryBlue = getSetting('primary_blue', '#1010D6');
$primaryRed = getSetting('primary_red', '#E30613');
$menus = getMenus();
$pageTitle = isset($pageTitle) ? $pageTitle . ' | ' . $siteName : $siteName;
$pageDescription = isset($pageDescription) ? $pageDescription : getSetting('meta_description');
$pageKeywords = isset($pageKeywords) ? $pageKeywords : getSetting('meta_keywords');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= clean($pageDescription) ?>">
    <meta name="keywords" content="<?= clean($pageKeywords ?? '') ?>">
    <meta property="og:title" content="<?= clean($pageTitle) ?>">
    <meta property="og:description" content="<?= clean($pageDescription) ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="ar_SA">
    <title><?= clean($pageTitle) ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">

    <style>
        :root {
            --primary-blue: <?= clean($primaryBlue) ?>;
            --primary-red: <?= clean($primaryRed) ?>;
            --primary-red-dark: <?= clean($primaryRed) ?>dd;
            --primary-blue-dark: <?= clean($primaryBlue) ?>dd;
        }
    </style>
</head>
<body>

<!-- Top Bar -->
<div class="top-bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="top-bar-info">
                    <span><i class="fas fa-phone-alt"></i> <?= clean(getSetting('phone')) ?></span>
                    <span class="mx-3"><i class="fas fa-envelope"></i> <?= clean(getSetting('email')) ?></span>
                </div>
            </div>
            <div class="col-md-6 text-start">
                <div class="top-bar-social">
                    <?php if ($fb = getSetting('facebook')): ?>
                        <a href="<?= clean($fb) ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <?php endif; ?>
                    <?php if ($tw = getSetting('twitter')): ?>
                        <a href="<?= clean($tw) ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                    <?php if ($ig = getSetting('instagram')): ?>
                        <a href="<?= clean($ig) ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                    <?php if ($yt = getSetting('youtube')): ?>
                        <a href="<?= clean($yt) ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                    <?php endif; ?>
                    <?php if ($wa = getSetting('whatsapp')): ?>
                        <a href="https://wa.me/<?= clean($wa) ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <span class="brand-text">
                <span class="brand-land">Land</span><span class="brand-scape">Scape</span>
                <small>Travel</small>
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php foreach ($menus as $menu): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= clean($menu['url']) ?>"><?= clean($menu['title']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a href="<?= BASE_URL ?>booking" class="btn btn-gradient-red btn-sm px-4">
                <i class="fas fa-calendar-check me-1"></i> احجز الآن
            </a>
        </div>
    </div>
</nav>
