<?php
/**
 * 404 Page
 */
$pageTitle = 'الصفحة غير موجودة';

include __DIR__ . '/../includes/header.php';
?>

<section class="thank-you-section section-padding">
    <div class="container">
        <div data-aos="fade-up">
            <div class="thank-you-icon" style="background: linear-gradient(135deg, var(--primary-red), var(--primary-red-dark));">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 style="color: var(--primary-blue); font-weight: 800;">404</h2>
            <h4 class="text-muted mb-4">عذراً، الصفحة التي تبحث عنها غير موجودة</h4>
            <a href="<?= BASE_URL ?>" class="btn btn-gradient-blue px-5">
                <i class="fas fa-home me-2"></i> العودة للرئيسية
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
