<?php
/**
 * Custom Page Template
 */
$customPage = $_GET['page_data'] ?? null;
if (!$customPage) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    return;
}

$pageTitle = $customPage['meta_title'] ?: $customPage['title'];
$pageDescription = $customPage['meta_description'] ?? '';

include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 data-aos="fade-up"><?= clean($customPage['title']) ?></h1>
        <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="100">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">الرئيسية</a></li>
                <li class="breadcrumb-item active"><?= clean($customPage['title']) ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Page Content -->
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10" data-aos="fade-up">
                <?= $customPage['content'] ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
