<?php
/**
 * Hajj Programs Page
 */
$pageTitle = 'برامج الحج';
$pageDescription = 'اكتشف أفضل برامج الحج مع لاند سكيب للسفر والسياحة - حج VIP، حج اقتصادي، حج مباشر';
$programs = getProgramsByType('hajj');
$categories = getCategoriesByType('hajj');

include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">برامج الحج</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">الرئيسية</a></li>
                <li class="breadcrumb-item active">برامج الحج</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Filters -->
<section class="section-padding">
    <div class="container">
        <div class="filters-bar" data-aos="fade-up" id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">التصنيف</label>
                    <select class="form-select" id="filterCategory">
                        <option value="">جميع التصنيفات</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= clean($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">السعر</label>
                    <select class="form-select" id="filterPrice">
                        <option value="">جميع الأسعار</option>
                        <option value="0-30000">أقل من 30,000 ر.س</option>
                        <option value="30000-50000">30,000 - 50,000 ر.س</option>
                        <option value="50000-80000">50,000 - 80,000 ر.س</option>
                        <option value="80000-">أكثر من 80,000 ر.س</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">المدة</label>
                    <select class="form-select" id="filterDuration">
                        <option value="">جميع المدد</option>
                        <option value="10">10 أيام</option>
                        <option value="12">12 يوم</option>
                        <option value="15">15 يوم</option>
                        <option value="20">20 يوم</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Programs Grid -->
        <div class="row g-4">
            <?php if (count($programs) > 0): ?>
                <?php foreach ($programs as $index => $program): ?>
                <div class="col-md-6 col-lg-4 program-item" 
                     data-category="<?= $program['category_id'] ?>" 
                     data-price="<?= $program['price'] ?>"
                     data-duration="<?= preg_replace('/[^0-9]/', '', $program['duration']) ?>"
                     data-aos="fade-up" data-aos-delay="<?= ($index % 3) * 100 ?>">
                    <div class="program-card">
                        <div class="card-img">
                            <?php if ($program['image'] && file_exists($program['image'])): ?>
                                <img src="<?= clean($program['image']) ?>" alt="<?= clean($program['title']) ?>">
                            <?php else: ?>
                                <div class="placeholder-img"><i class="fas fa-kaaba"></i></div>
                            <?php endif; ?>
                            <span class="card-badge"><?= clean($program['category_name'] ?? 'حج') ?></span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= clean($program['title']) ?></h5>
                            <p class="card-text"><?= clean($program['short_description']) ?></p>
                            <div class="card-meta">
                                <span class="card-price"><?= formatPrice($program['price']) ?></span>
                                <span class="card-duration">
                                    <i class="fas fa-clock"></i> <?= clean($program['duration']) ?>
                                </span>
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                <a href="<?= BASE_URL ?>program/<?= clean($program['slug']) ?>" class="btn btn-gradient-blue btn-sm flex-fill">
                                    التفاصيل
                                </a>
                                <a href="<?= BASE_URL ?>booking?program=<?= $program['id'] ?>" class="btn btn-gradient-red btn-sm flex-fill">
                                    احجز الآن
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-kaaba fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">لا توجد برامج حج متاحة حالياً</h4>
                    <p class="text-muted">يرجى العودة لاحقاً أو التواصل معنا للمزيد من المعلومات</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container" data-aos="fade-up">
        <h2>لم تجد ما تبحث عنه؟</h2>
        <p>تواصل معنا وسنساعدك في اختيار البرنامج المناسب لك</p>
        <a href="<?= BASE_URL ?>contact" class="btn btn-outline-custom btn-lg px-5">
            <i class="fas fa-phone-alt me-2"></i> تواصل معنا
        </a>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
