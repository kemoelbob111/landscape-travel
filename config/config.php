<?php
/**
 * Main Configuration
 * Land Scape Travel
 */

session_start();

// Base URL - change this to your domain
define('BASE_URL', '/');
define('ADMIN_URL', '/admin/');

// File upload paths
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('ASSETS_PATH', __DIR__ . '/../assets/');

// Include database
require_once __DIR__ . '/database.php';

/**
 * Get site setting from database
 */
function getSetting(string $key, string $default = ''): string {
    static $settings = null;
    if ($settings === null) {
        try {
            $db = getDB();
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
            $settings = [];
            while ($row = $stmt->fetch()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (PDOException $e) {
            return $default;
        }
    }
    return $settings[$key] ?? $default;
}

/**
 * Get all settings
 */
function getAllSettings(): array {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Clean input for XSS protection
 */
function clean(string $data): string {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate a slug from text
 */
function generateSlug(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Redirect helper
 */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

/**
 * Check if admin is logged in
 */
function isAdminLoggedIn(): bool {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Get resolved URL slug
 */
function getResolvedSlug(string $slug): string {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT original_slug FROM url_slugs WHERE custom_slug = ?");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ? $row['original_slug'] : $slug;
    } catch (PDOException $e) {
        return $slug;
    }
}

/**
 * Get custom slug for original
 */
function getCustomSlug(string $original): string {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT custom_slug FROM url_slugs WHERE original_slug = ?");
        $stmt->execute([$original]);
        $row = $stmt->fetch();
        return $row ? $row['custom_slug'] : $original;
    } catch (PDOException $e) {
        return $original;
    }
}

/**
 * Get menus from database
 */
function getMenus(): array {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM menus WHERE is_active = 1 ORDER BY sort_order ASC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Format price
 */
function formatPrice(float $price): string {
    return number_format($price, 0) . ' ر.س';
}

/**
 * Get programs by type
 */
function getProgramsByType(string $type, int $limit = 0, bool $featuredOnly = false): array {
    try {
        $db = getDB();
        $sql = "SELECT p.*, c.name as category_name FROM programs p LEFT JOIN categories c ON p.category_id = c.id WHERE p.type = ? AND p.is_active = 1";
        if ($featuredOnly) {
            $sql .= " AND p.is_featured = 1";
        }
        $sql .= " ORDER BY p.sort_order ASC, p.created_at DESC";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        $stmt = $db->prepare($sql);
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get featured programs
 */
function getFeaturedPrograms(int $limit = 6): array {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM programs p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1 AND p.is_featured = 1 ORDER BY p.sort_order ASC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get program by slug
 */
function getProgramBySlug(string $slug): ?array {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM programs p LEFT JOIN categories c ON p.category_id = c.id WHERE p.slug = ?");
        $stmt->execute([$slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Get testimonials
 */
function getTestimonials(): array {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY sort_order ASC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get articles
 */
function getArticles(int $limit = 0): array {
    try {
        $db = getDB();
        $sql = "SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.is_published = 1 ORDER BY a.created_at DESC";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get article by slug
 */
function getArticleBySlug(string $slug): ?array {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.slug = ?");
        $stmt->execute([$slug]);
        $result = $stmt->fetch();
        return $result ?: null;
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Get banners
 */
function getBanners(): array {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM banners WHERE is_active = 1 ORDER BY sort_order ASC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get categories by type
 */
function getCategoriesByType(string $type): array {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM categories WHERE type = ? AND is_active = 1 ORDER BY sort_order ASC");
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}
