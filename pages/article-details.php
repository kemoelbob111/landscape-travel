<?php
/**
 * Article Details Page
 */
$slug = $_GET['slug'] ?? '';
$article = getArticleBySlug($slug);

if (!$article) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    return;
}

// Increment views
try {
    $db = getDB();
    $db->prepare("UPDATE articles SET views = views + 1 WHERE id = ?")->execute([$article['id']]);
} catch (PDOException $e) {}

$pageTitle = $article['title'];
$pageDescription = $article['excerpt'];

include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up"><?= clean($article['title']) ?></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>blog">المدونة</a></li>
                <li class="breadcrumb-item active"><?= clean($article['title']) ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Article Content -->
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if ($article['image'] && file_exists($article['image'])): ?>
                <div class="mb-4" data-aos="fade-up">
                    <img src="<?= clean($article['image']) ?>" alt="<?= clean($article['title']) ?>" 
                         style="width:100%; border-radius: var(--radius); box-shadow: var(--shadow);">
                </div>
                <?php endif; ?>

                <div class="d-flex gap-3 mb-4 text-muted" data-aos="fade-up">
                    <span><i class="fas fa-calendar-alt me-1"></i> <?= date('Y/m/d', strtotime($article['created_at'])) ?></span>
                    <?php if ($article['author']): ?>
                        <span><i class="fas fa-user me-1"></i> <?= clean($article['author']) ?></span>
                    <?php endif; ?>
                    <?php if ($article['category_name']): ?>
                        <span><i class="fas fa-folder me-1"></i> <?= clean($article['category_name']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="article-content" data-aos="fade-up">
                    <?= $article['content'] ?>
                </div>

                <hr class="my-5">

                <div class="text-center">
                    <a href="<?= BASE_URL ?>blog" class="btn btn-gradient-blue">
                        <i class="fas fa-arrow-right me-2"></i> العودة للمدونة
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
