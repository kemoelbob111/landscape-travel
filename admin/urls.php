<?php
/**
 * Admin URL Control
 */
$adminPageTitle = 'التحكم بالروابط';
$db = getDB();
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slugs = $_POST['slugs'] ?? [];
    foreach ($slugs as $id => $customSlug) {
        $customSlug = trim($customSlug);
        if (!empty($customSlug)) {
            $db->prepare("UPDATE url_slugs SET custom_slug = ? WHERE id = ?")->execute([$customSlug, $id]);
        }
    }
    $success = 'تم تحديث الروابط بنجاح';
}

// Handle add
if (isset($_POST['add_slug'])) {
    $original = trim($_POST['original_slug'] ?? '');
    $custom = trim($_POST['custom_slug'] ?? '');
    $type = trim($_POST['entity_type'] ?? 'page');
    if (!empty($original) && !empty($custom)) {
        $db->prepare("INSERT INTO url_slugs (original_slug, custom_slug, entity_type) VALUES (?,?,?) ON DUPLICATE KEY UPDATE custom_slug = ?")->execute([$original, $custom, $type, $custom]);
        $success = 'تم الإضافة بنجاح';
    }
}

$slugs = $db->query("SELECT * FROM url_slugs ORDER BY id ASC")->fetchAll();
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-link me-2"></i> التحكم بالروابط (URL Control)</h4>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="admin-form mb-4">
    <p class="text-muted mb-4">يمكنك تغيير روابط الصفحات كما تريد. مثال: تغيير <code>/umrah</code> إلى <code>/omra-offers</code></p>
    
    <form method="POST">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>الرابط الأصلي</th><th>الرابط المخصص</th><th>النوع</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($slugs as $s): ?>
                    <tr>
                        <td dir="ltr" class="text-muted">/<?= clean($s['original_slug']) ?></td>
                        <td>
                            <input type="text" name="slugs[<?= $s['id'] ?>]" class="form-control form-control-sm" 
                                   value="<?= clean($s['custom_slug']) ?>" dir="ltr">
                        </td>
                        <td><?= clean($s['entity_type']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-admin-primary"><i class="fas fa-save me-2"></i> حفظ التغييرات</button>
    </form>
</div>

<div class="admin-form">
    <h6 class="fw-bold mb-3">إضافة رابط جديد</h6>
    <form method="POST">
        <div class="row g-3">
            <div class="col-md-4"><input type="text" name="original_slug" class="form-control" placeholder="الرابط الأصلي (مثل: services)" dir="ltr"></div>
            <div class="col-md-4"><input type="text" name="custom_slug" class="form-control" placeholder="الرابط المخصص (مثل: our-services)" dir="ltr"></div>
            <div class="col-md-2">
                <select name="entity_type" class="form-select"><option value="page">صفحة</option><option value="program">برنامج</option><option value="category">تصنيف</option></select>
            </div>
            <div class="col-md-2"><button type="submit" name="add_slug" value="1" class="btn btn-admin-primary w-100">إضافة</button></div>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
