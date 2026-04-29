<?php
/**
 * Land Scape Travel - Main Router
 */
define('PAGE_LOADED', true);
require_once __DIR__ . '/config/config.php';

// Get the request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/');

if (empty($requestUri) || $requestUri === '') {
    $requestUri = '/';
}

// Resolve custom slugs
$slug = ltrim($requestUri, '/');
if ($slug) {
    $resolvedSlug = getResolvedSlug($slug);
} else {
    $resolvedSlug = '';
}

// Route the request
switch (true) {
    case $requestUri === '/' || $requestUri === '':
        require __DIR__ . '/pages/home.php';
        break;

    case $resolvedSlug === 'umrah':
        require __DIR__ . '/pages/umrah.php';
        break;

    case $resolvedSlug === 'hajj':
        require __DIR__ . '/pages/hajj.php';
        break;

    case $resolvedSlug === 'about':
        require __DIR__ . '/pages/about.php';
        break;

    case $resolvedSlug === 'blog':
        require __DIR__ . '/pages/blog.php';
        break;

    case $resolvedSlug === 'contact':
        require __DIR__ . '/pages/contact.php';
        break;

    case $resolvedSlug === 'booking':
        require __DIR__ . '/pages/booking.php';
        break;

    case $requestUri === '/thank-you':
        require __DIR__ . '/pages/thank-you.php';
        break;

    case preg_match('#^/program/(.+)$#', $requestUri, $matches) === 1:
        $_GET['slug'] = $matches[1];
        require __DIR__ . '/pages/program-details.php';
        break;

    case preg_match('#^/article/(.+)$#', $requestUri, $matches) === 1:
        $_GET['slug'] = $matches[1];
        require __DIR__ . '/pages/article-details.php';
        break;

    case strpos($requestUri, '/admin') === 0:
        // Admin routes handled by admin/index.php
        require __DIR__ . '/admin/index.php';
        break;

    default:
        // Try to find program or article by slug
        $program = getProgramBySlug($slug);
        if ($program) {
            $_GET['slug'] = $slug;
            require __DIR__ . '/pages/program-details.php';
            break;
        }

        $article = getArticleBySlug($slug);
        if ($article) {
            $_GET['slug'] = $slug;
            require __DIR__ . '/pages/article-details.php';
            break;
        }

        // Check custom pages
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM pages WHERE slug = ? AND is_published = 1");
            $stmt->execute([$slug]);
            $customPage = $stmt->fetch();
            if ($customPage) {
                $_GET['page_data'] = $customPage;
                require __DIR__ . '/pages/custom-page.php';
                break;
            }
        } catch (PDOException $e) {
            // Fall through to 404
        }

        http_response_code(404);
        require __DIR__ . '/pages/404.php';
        break;
}
