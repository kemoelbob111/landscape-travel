<?php
/**
 * About Page
 */
$pageTitle = 'من نحن';
$pageDescription = 'تعرف على شركة لاند سكيب للسفر والسياحة - رؤيتنا ورسالتنا وخبرتنا في مجال الحج والعمرة';

// Get about page content
try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM pages WHERE slug = 'about'");
    $stmt->execute();
    $aboutPage = $stmt->fetch();
} catch (PDOException $e) {
    $aboutPage = null;
}

include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">من نحن</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">الرئيسية</a></li>
                <li class="breadcrumb-item active">من نحن</li>
            </ol>
        </nav>
    </div>
</section>

<!-- About Content -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-up">
                <div class="placeholder-img" style="min-height:400px; border-radius: var(--radius);">
                    <i class="fas fa-kaaba"></i>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <h2 style="color: var(--primary-blue); font-weight: 800;">شركة لاند سكيب للسفر والسياحة</h2>
                <p class="lead mt-3" style="color: var(--text-muted);">
                    إحدى الشركات الرائدة في مجال تنظيم رحلات الحج والعمرة
                </p>
                <p>
                    تأسست شركة لاند سكيب للسفر والسياحة بهدف تقديم أفضل الخدمات في مجال رحلات الحج والعمرة. 
                    نسعى دائماً لتوفير تجربة روحانية متكاملة لعملائنا الكرام مع الحرص على أعلى معايير الجودة والراحة.
                </p>
                <p>
                    مع خبرة تزيد عن 15 عاماً في هذا المجال، قمنا بخدمة آلاف العملاء وتنظيم مئات الرحلات الناجحة. 
                    فريقنا المتخصص يعمل بجد لضمان رضا كل عميل وتقديم أفضل تجربة ممكنة.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="section-padding" style="background: var(--light-gray);">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6" data-aos="fade-up">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="fas fa-eye"></i></div>
                    <h5>رؤيتنا</h5>
                    <p>أن نكون الخيار الأول والأفضل في مجال رحلات الحج والعمرة على مستوى المنطقة، 
                    وأن نساهم في تسهيل أداء المناسك لجميع المسلمين بأعلى مستويات الخدمة والراحة.</p>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card h-100">
                    <div class="feature-icon"><i class="fas fa-bullseye"></i></div>
                    <h5>رسالتنا</h5>
                    <p>تقديم تجربة روحانية متكاملة للحجاج والمعتمرين مع أعلى معايير الجودة والراحة، 
                    والسعي المستمر لتطوير خدماتنا وتلبية تطلعات عملائنا.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Us -->
<section class="section-padding">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>لماذا تختارنا؟</h2>
            <p>مزايا تجعلنا الخيار الأمثل لرحلتك</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-trophy"></i></div>
                    <h5>خبرة طويلة</h5>
                    <p>أكثر من 15 عاماً من الخبرة في تنظيم رحلات الحج والعمرة</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-users"></i></div>
                    <h5>فريق محترف</h5>
                    <p>فريق عمل متخصص ومدرب على أعلى مستوى لخدمتكم</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <h5>ثقة وأمان</h5>
                    <p>شركة مرخصة ومعتمدة مع ضمان حقوق العملاء</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-hotel"></i></div>
                    <h5>فنادق مميزة</h5>
                    <p>شراكات مع أفضل الفنادق القريبة من الحرم</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-wallet"></i></div>
                    <h5>أسعار منافسة</h5>
                    <p>أفضل الأسعار في السوق مع عدم التنازل عن الجودة</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-headset"></i></div>
                    <h5>دعم متواصل</h5>
                    <p>خدمة عملاء متوفرة 24/7 لمساعدتك في أي وقت</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
