<?php
/**
 * Contact Page
 */
$pageTitle = 'اتصل بنا';
$pageDescription = 'تواصل مع شركة لاند سكيب للسفر والسياحة - نحن هنا لخدمتك';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($message)) {
        $error = 'يرجى ملء جميع الحقول المطلوبة';
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            $success = 'تم إرسال رسالتك بنجاح. سنتواصل معك في أقرب وقت.';
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.';
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">اتصل بنا</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">الرئيسية</a></li>
                <li class="breadcrumb-item active">اتصل بنا</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3" data-aos="fade-up">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h6>العنوان</h6>
                    <p class="text-muted mb-0"><?= clean(getSetting('address')) ?></p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                    <h6>الهاتف</h6>
                    <p class="text-muted mb-0">
                        <a href="tel:<?= clean(getSetting('phone')) ?>"><?= clean(getSetting('phone')) ?></a>
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fab fa-whatsapp"></i></div>
                    <h6>واتساب</h6>
                    <p class="text-muted mb-0">
                        <a href="https://wa.me/<?= clean(getSetting('whatsapp')) ?>"><?= clean(getSetting('whatsapp')) ?></a>
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="contact-card">
                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                    <h6>البريد الإلكتروني</h6>
                    <p class="text-muted mb-0">
                        <a href="mailto:<?= clean(getSetting('email')) ?>"><?= clean(getSetting('email')) ?></a>
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Contact Form -->
            <div class="col-lg-7" data-aos="fade-up">
                <div class="contact-form">
                    <h4 class="mb-4" style="color: var(--primary-blue);">أرسل لنا رسالة</h4>

                    <?php if ($success): ?>
                        <div class="alert alert-custom-success mb-4"><?= clean($success) ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-custom-error mb-4"><?= clean($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">الاسم الكامل *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="tel" name="phone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الموضوع</label>
                                <input type="text" name="subject" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">الرسالة *</label>
                                <textarea name="message" class="form-control" rows="5" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gradient-red px-5">
                                    <i class="fas fa-paper-plane me-2"></i> إرسال الرسالة
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Map -->
            <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                <?php $mapEmbed = getSetting('google_map_embed'); ?>
                <?php if ($mapEmbed): ?>
                    <div style="border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); height: 100%; min-height: 400px;">
                        <?= $mapEmbed ?>
                    </div>
                <?php else: ?>
                    <div class="placeholder-img" style="min-height:400px; border-radius: var(--radius);">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
