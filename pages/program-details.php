<?php
/**
 * Program Details Page
 */
$slug = $_GET['slug'] ?? '';
$program = getProgramBySlug($slug);

if (!$program) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    return;
}

// Increment views
try {
    $db = getDB();
    $db->prepare("UPDATE programs SET views = views + 1 WHERE id = ?")->execute([$program['id']]);
} catch (PDOException $e) {}

$pageTitle = $program['title'];
$pageDescription = $program['short_description'];

include __DIR__ . '/../includes/header.php';
$whatsapp = getSetting('whatsapp');
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up"><?= clean($program['title']) ?></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">الرئيسية</a></li>
                <li class="breadcrumb-item">
                    <a href="<?= BASE_URL . ($program['type'] === 'hajj' ? 'hajj' : 'umrah') ?>">
                        <?= $program['type'] === 'hajj' ? 'برامج الحج' : 'برامج العمرة' ?>
                    </a>
                </li>
                <li class="breadcrumb-item active"><?= clean($program['title']) ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Program Details -->
<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Image -->
                <div class="program-detail-img mb-4" data-aos="fade-up">
                    <?php if ($program['image'] && file_exists($program['image'])): ?>
                        <img src="<?= clean($program['image']) ?>" alt="<?= clean($program['title']) ?>">
                    <?php else: ?>
                        <div class="placeholder-img" style="min-height:400px">
                            <i class="fas fa-<?= $program['type'] === 'hajj' ? 'kaaba' : 'mosque' ?>"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tabs -->
                <div class="program-tabs" data-aos="fade-up">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#description">الوصف</button>
                        </li>
                        <?php if ($program['itinerary']): ?>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#itinerary">البرنامج اليومي</button>
                        </li>
                        <?php endif; ?>
                        <?php if ($program['hotels']): ?>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#hotels">الفنادق</button>
                        </li>
                        <?php endif; ?>
                        <?php if ($program['includes'] || $program['excludes']): ?>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#includes">يشمل / لا يشمل</button>
                        </li>
                        <?php endif; ?>
                        <?php if ($program['terms']): ?>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#terms">الشروط</button>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content p-4">
                        <div class="tab-pane fade show active" id="description">
                            <?= $program['description'] ?>
                            <?php if ($program['features']): ?>
                                <h5 class="mt-4 mb-3">المميزات</h5>
                                <?= $program['features'] ?>
                            <?php endif; ?>
                        </div>
                        <?php if ($program['itinerary']): ?>
                        <div class="tab-pane fade" id="itinerary">
                            <?= $program['itinerary'] ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($program['hotels']): ?>
                        <div class="tab-pane fade" id="hotels">
                            <?= $program['hotels'] ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($program['includes'] || $program['excludes']): ?>
                        <div class="tab-pane fade" id="includes">
                            <?php if ($program['includes']): ?>
                                <h5 class="mb-3" style="color: #28a745;"><i class="fas fa-check-circle me-2"></i>يشمل البرنامج</h5>
                                <?= $program['includes'] ?>
                            <?php endif; ?>
                            <?php if ($program['excludes']): ?>
                                <h5 class="mt-4 mb-3" style="color: var(--primary-red);"><i class="fas fa-times-circle me-2"></i>لا يشمل البرنامج</h5>
                                <?= $program['excludes'] ?>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($program['terms']): ?>
                        <div class="tab-pane fade" id="terms">
                            <?= $program['terms'] ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="program-info-box" data-aos="fade-up" data-aos-delay="100">
                    <!-- Price -->
                    <div class="price-tag">
                        <div class="price-label">السعر يبدأ من</div>
                        <div class="price"><?= formatPrice($program['price']) ?></div>
                        <?php if ($program['price_note']): ?>
                            <div class="price-label"><?= clean($program['price_note']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Info -->
                    <ul class="info-list">
                        <li>
                            <i class="fas fa-clock"></i>
                            <span><strong>المدة:</strong> <?= clean($program['duration']) ?></span>
                        </li>
                        <li>
                            <i class="fas fa-tag"></i>
                            <span><strong>النوع:</strong> <?= $program['type'] === 'hajj' ? 'حج' : 'عمرة' ?></span>
                        </li>
                        <?php if ($program['category_name']): ?>
                        <li>
                            <i class="fas fa-folder"></i>
                            <span><strong>التصنيف:</strong> <?= clean($program['category_name']) ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <!-- Booking Button -->
                    <a href="<?= BASE_URL ?>booking?program=<?= $program['id'] ?>" class="btn btn-gradient-red w-100 mb-3">
                        <i class="fas fa-calendar-check me-2"></i> احجز الآن
                    </a>

                    <!-- WhatsApp Button -->
                    <?php if ($whatsapp): ?>
                    <a href="https://wa.me/<?= clean($whatsapp) ?>?text=<?= urlencode('أرغب في الاستفسار عن برنامج: ' . $program['title']) ?>" 
                       target="_blank" class="btn btn-success w-100">
                        <i class="fab fa-whatsapp me-2"></i> استفسر عبر واتساب
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
