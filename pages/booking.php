<?php
/**
 * Booking Page
 */
$pageTitle = 'احجز الآن';
$pageDescription = 'احجز رحلتك للحج أو العمرة مع لاند سكيب للسفر والسياحة';

$success = '';
$error = '';

// Get all active programs for the dropdown
try {
    $db = getDB();
    $allPrograms = $db->query("SELECT id, title, type FROM programs WHERE is_active = 1 ORDER BY type, sort_order")->fetchAll();
} catch (PDOException $e) {
    $allPrograms = [];
}

$selectedProgram = $_GET['program'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $programId = intval($_POST['program_id'] ?? 0);
    $persons = intval($_POST['persons'] ?? 1);
    $notes = trim($_POST['notes'] ?? '');

    if (empty($name) || empty($phone)) {
        $error = 'يرجى ملء الحقول المطلوبة (الاسم ورقم الهاتف)';
    } else {
        try {
            $db = getDB();
            
            // Get program title
            $programTitle = '';
            if ($programId > 0) {
                $stmt = $db->prepare("SELECT title FROM programs WHERE id = ?");
                $stmt->execute([$programId]);
                $prog = $stmt->fetch();
                $programTitle = $prog ? $prog['title'] : '';
            }

            $stmt = $db->prepare("INSERT INTO bookings (program_id, name, phone, whatsapp, email, program_title, persons, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $programId ?: null,
                $name,
                $phone,
                $whatsapp,
                $email,
                $programTitle,
                $persons,
                $notes
            ]);
            
            redirect(BASE_URL . 'thank-you');
        } catch (PDOException $e) {
            $error = 'حدث خطأ أثناء إرسال الحجز. يرجى المحاولة مرة أخرى.';
        }
    }
}

include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up">احجز رحلتك الآن</h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">الرئيسية</a></li>
                <li class="breadcrumb-item active">الحجز</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Booking Form -->
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="booking-form">
                    <h4 class="mb-2" style="color: var(--primary-blue);">نموذج الحجز</h4>
                    <p class="text-muted mb-4">يرجى ملء البيانات التالية وسنتواصل معك في أقرب وقت</p>

                    <?php if ($error): ?>
                        <div class="alert alert-custom-error mb-4"><?= clean($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">الاسم الكامل *</label>
                                <input type="text" name="name" class="form-control" required 
                                       placeholder="أدخل اسمك الكامل">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم الهاتف *</label>
                                <input type="tel" name="phone" class="form-control" required 
                                       placeholder="أدخل رقم هاتفك">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم واتساب</label>
                                <input type="tel" name="whatsapp" class="form-control" 
                                       placeholder="أدخل رقم واتساب">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control" 
                                       placeholder="أدخل بريدك الإلكتروني">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البرنامج</label>
                                <select name="program_id" class="form-select">
                                    <option value="">-- اختر البرنامج --</option>
                                    <optgroup label="برامج العمرة">
                                        <?php foreach ($allPrograms as $p): ?>
                                            <?php if ($p['type'] === 'umrah'): ?>
                                                <option value="<?= $p['id'] ?>" <?= $selectedProgram == $p['id'] ? 'selected' : '' ?>>
                                                    <?= clean($p['title']) ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="برامج الحج">
                                        <?php foreach ($allPrograms as $p): ?>
                                            <?php if ($p['type'] === 'hajj'): ?>
                                                <option value="<?= $p['id'] ?>" <?= $selectedProgram == $p['id'] ? 'selected' : '' ?>>
                                                    <?= clean($p['title']) ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">عدد الأفراد</label>
                                <input type="number" name="persons" class="form-control" value="1" min="1" max="50">
                            </div>
                            <div class="col-12">
                                <label class="form-label">ملاحظات إضافية</label>
                                <textarea name="notes" class="form-control" rows="4" 
                                          placeholder="أي ملاحظات أو طلبات خاصة..."></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gradient-red btn-lg px-5 w-100">
                                    <i class="fas fa-paper-plane me-2"></i> إرسال الحجز
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
