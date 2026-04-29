<?php
/**
 * Thank You Page
 */
$pageTitle = 'شكراً لك';

include __DIR__ . '/../includes/header.php';
?>

<section class="thank-you-section section-padding">
    <div class="container">
        <div data-aos="fade-up">
            <div class="thank-you-icon">
                <i class="fas fa-check"></i>
            </div>
            <h2 style="color: var(--primary-blue); font-weight: 800;">شكراً لك!</h2>
            <p class="lead text-muted mt-3 mb-4">
                تم استلام طلبك بنجاح. سيقوم فريقنا بالتواصل معك في أقرب وقت ممكن.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?= BASE_URL ?>" class="btn btn-gradient-blue px-4">
                    <i class="fas fa-home me-2"></i> العودة للرئيسية
                </a>
                <?php if ($wa = getSetting('whatsapp')): ?>
                <a href="https://wa.me/<?= clean($wa) ?>" target="_blank" class="btn btn-success px-4">
                    <i class="fab fa-whatsapp me-2"></i> تواصل واتساب
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
