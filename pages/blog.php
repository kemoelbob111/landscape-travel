<?php
/**
 * Blog Page
 */
$pageTitle = 'المدونة';
$pageDescription = 'نصائح ومقالات مفيدة للحجاج والمعتمرين - لاند سكيب للسفر والسياحة';
$articles = getArticles();

include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">المدونة</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">الرئيسية</a></li>
                <li class="breadcrumb-item active">المدونة</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Blog Content -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <?php if (count($articles) > 0): ?>
                <?php foreach ($articles as $index => $article): ?>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= ($index % 3) * 100 ?>">
                    <div class="blog-card">
                        <div class="blog-img">
                            <?php if ($article['image'] && file_exists($article['image'])): ?>
                                <img src="<?= clean($article['image']) ?>" alt="<?= clean($article['title']) ?>">
                            <?php else: ?>
                                <div class="placeholder-img" style="min-height:200px"><i class="fas fa-newspaper"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="blog-body">
                            <span class="blog-category"><?= clean($article['category_name'] ?? '') ?></span>
                            <h5 class="blog-title">
                                <a href="<?= BASE_URL ?>article/<?= clean($article['slug']) ?>"><?= clean($article['title']) ?></a>
                            </h5>
                            <p class="blog-excerpt"><?= clean($article['excerpt']) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="blog-date">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    <?= date('Y/m/d', strtotime($article['created_at'])) ?>
                                </span>
                                <a href="<?= BASE_URL ?>article/<?= clean($article['slug']) ?>" class="btn btn-gradient-blue btn-sm">
                                    اقرأ المزيد
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد مقالات حالياً</h4>
                    <p class="text-muted">يرجى العودة لاحقاً</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
