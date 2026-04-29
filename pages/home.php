<?php
/**
 * Home Page
 */
$pageTitle = getSetting('site_tagline', 'رحلتك للحج والعمرة تبدأ من هنا');
$banners = getBanners();
$featuredPrograms = getFeaturedPrograms(6);
$umrahPrograms = getProgramsByType('umrah', 3);
$hajjPrograms = getProgramsByType('hajj', 3);
$testimonials = getTestimonials();
$articles = getArticles(3);

include __DIR__ . '/../includes/header.php';
?>

<!-- Hero Section -->
<?php if (count($banners) > 0): ?>
<div class="swiper hero-swiper">
    <div class="swiper-wrapper">
        <?php foreach ($banners as $banner): ?>
        <div class="swiper-slide" style="background-image: url('<?= clean($banner['image']) ?>')">
            <div class="container">
                <div class="hero-content" data-aos="fade-up">
                    <h1><?= clean($banner['title']) ?></h1>
                    <p><?= clean($banner['subtitle']) ?></p>
                    <div class="hero-buttons">
                        <a href="<?= BASE_URL ?>booking" class="btn btn-gradient-red btn-lg">
                            <i class="fas fa-calendar-check me-2"></i> احجز الآن
                        </a>
                        <a href="#programs" class="btn btn-outline-custom btn-lg">
                            <i class="fas fa-th-list me-2"></i> شاهد البرامج
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-pagination"></div>
</div>
<?php else: ?>
<section class="hero-section">
    <div class="container">
        <div class="hero-content" data-aos="fade-up">
            <h1>رحلتك للحج والعمرة تبدأ من هنا</h1>
            <p>اكتشف أفضل البرامج والعروض الحصرية مع لاند سكيب للسفر والسياحة</p>
            <div class="hero-buttons">
                <a href="<?= BASE_URL ?>booking" class="btn btn-gradient-red btn-lg">
                    <i class="fas fa-calendar-check me-2"></i> احجز الآن
                </a>
                <a href="#programs" class="btn btn-outline-custom btn-lg">
                    <i class="fas fa-th-list me-2"></i> شاهد البرامج
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <span class="stat-number" data-count="15000">0</span>
                    <div class="stat-label">عميل سعيد</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-plane"></i></div>
                    <span class="stat-number" data-count="500">0</span>
                    <div class="stat-label">رحلة ناجحة</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-star"></i></div>
                    <span class="stat-number" data-count="15">0</span>
                    <div class="stat-label">سنة خبرة</div>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-award"></i></div>
                    <span class="stat-number" data-count="50">0</span>
                    <div class="stat-label">برنامج متنوع</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Programs -->
<section class="section-padding" id="programs">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>عروضنا المميزة</h2>
            <p>اختر من بين مجموعة متنوعة من البرامج المصممة خصيصاً لتلبية احتياجاتك</p>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredPrograms as $index => $program): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="program-card">
                    <div class="card-img">
                        <?php if ($program['image'] && file_exists($program['image'])): ?>
                            <img src="<?= clean($program['image']) ?>" alt="<?= clean($program['title']) ?>">
                        <?php else: ?>
                            <div class="placeholder-img"><i class="fas fa-kaaba"></i></div>
                        <?php endif; ?>
                        <span class="card-badge">
                            <?= $program['type'] === 'hajj' ? 'حج' : 'عمرة' ?>
                        </span>
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
                        <a href="<?= BASE_URL ?>program/<?= clean($program['slug']) ?>" class="btn btn-gradient-red btn-sm w-100 mt-3">
                            التفاصيل والحجز
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Umrah Programs -->
<?php if (count($umrahPrograms) > 0): ?>
<section class="section-padding" style="background: var(--light-gray);">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>برامج العمرة</h2>
            <p>برامج عمرة متنوعة تناسب جميع الميزانيات والاحتياجات</p>
        </div>
        <div class="row g-4">
            <?php foreach ($umrahPrograms as $index => $program): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="program-card">
                    <div class="card-img">
                        <?php if ($program['image'] && file_exists($program['image'])): ?>
                            <img src="<?= clean($program['image']) ?>" alt="<?= clean($program['title']) ?>">
                        <?php else: ?>
                            <div class="placeholder-img"><i class="fas fa-mosque"></i></div>
                        <?php endif; ?>
                        <span class="card-badge"><?= clean($program['category_name'] ?? 'عمرة') ?></span>
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
                        <a href="<?= BASE_URL ?>program/<?= clean($program['slug']) ?>" class="btn btn-gradient-blue btn-sm w-100 mt-3">
                            التفاصيل والحجز
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?= BASE_URL ?>umrah" class="btn btn-gradient-blue px-5">
                عرض جميع برامج العمرة <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Hajj Programs -->
<?php if (count($hajjPrograms) > 0): ?>
<section class="section-padding">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>برامج الحج</h2>
            <p>برامج حج متكاملة مع خدمات عالية الجودة</p>
        </div>
        <div class="row g-4">
            <?php foreach ($hajjPrograms as $index => $program): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
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
                        <a href="<?= BASE_URL ?>program/<?= clean($program['slug']) ?>" class="btn btn-gradient-red btn-sm w-100 mt-3">
                            التفاصيل والحجز
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="<?= BASE_URL ?>hajj" class="btn btn-gradient-red px-5">
                عرض جميع برامج الحج <i class="fas fa-arrow-left me-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Why Choose Us -->
<section class="section-padding why-us-section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>لماذا تختار لاند سكيب؟</h2>
            <p>نحن نقدم لك تجربة فريدة ومتميزة في رحلات الحج والعمرة</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-hotel"></i></div>
                    <h5>فنادق مميزة</h5>
                    <p>إقامة في أفضل الفنادق القريبة من الحرم مع خدمات فندقية متكاملة</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-hand-holding-heart"></i></div>
                    <h5>رعاية شاملة</h5>
                    <p>مرشدون دينيون متخصصون ورعاية طبية على مدار الساعة</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-tags"></i></div>
                    <h5>أسعار تنافسية</h5>
                    <p>نقدم أفضل الأسعار مع ضمان جودة الخدمات المقدمة</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-bus"></i></div>
                    <h5>تنقلات مريحة</h5>
                    <p>حافلات حديثة ومكيفة لجميع التنقلات بين المشاعر المقدسة</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-headset"></i></div>
                    <h5>دعم 24/7</h5>
                    <p>فريق دعم متواجد على مدار الساعة لخدمتكم في أي وقت</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-certificate"></i></div>
                    <h5>خبرة طويلة</h5>
                    <p>أكثر من 15 عاماً من الخبرة في تنظيم رحلات الحج والعمرة</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<?php if (count($testimonials) > 0): ?>
<section class="section-padding testimonials-section">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2 style="color: white;">آراء عملائنا</h2>
            <p style="color: rgba(255,255,255,0.7);">ماذا يقول عملاؤنا عن تجربتهم معنا</p>
        </div>
        <div class="swiper testimonials-swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php foreach ($testimonials as $testimonial): ?>
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="stars">
                            <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="quote">"<?= clean($testimonial['content']) ?>"</p>
                        <div class="client-name"><?= clean($testimonial['name']) ?></div>
                        <div class="client-title"><?= clean($testimonial['title'] ?? '') ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Blog Section -->
<?php if (count($articles) > 0): ?>
<section class="section-padding">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>من المدونة</h2>
            <p>نصائح ومقالات مفيدة للحجاج والمعتمرين</p>
        </div>
        <div class="row g-4">
            <?php foreach ($articles as $index => $article): ?>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
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
                        <span class="blog-date">
                            <i class="fas fa-calendar-alt me-1"></i>
                            <?= date('Y/m/d', strtotime($article['created_at'])) ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container" data-aos="fade-up">
        <h2>هل أنت مستعد لرحلتك القادمة؟</h2>
        <p>تواصل معنا الآن واحجز مكانك في أفضل برامج الحج والعمرة</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?= BASE_URL ?>booking" class="btn btn-outline-custom btn-lg px-5">
                <i class="fas fa-calendar-check me-2"></i> احجز الآن
            </a>
            <?php if ($wa = getSetting('whatsapp')): ?>
            <a href="https://wa.me/<?= clean($wa) ?>" target="_blank" class="btn btn-outline-custom btn-lg px-5">
                <i class="fab fa-whatsapp me-2"></i> تواصل واتساب
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
