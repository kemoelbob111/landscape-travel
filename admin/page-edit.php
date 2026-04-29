<?php
/**
 * Admin Page Add/Edit
 */
$adminPageTitle = 'إدارة الصفحة';
$db = getDB();
$id = intval($_GET['id'] ?? 0);
$page = null;
$success = '';

if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM pages WHERE id = ?");
    $stmt->execute([$id]);
    $page = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: generateSlug($title);
    $content = $_POST['content'] ?? '';
    $metaTitle = trim($_POST['meta_title'] ?? '');
    $metaDesc = trim($_POST['meta_description'] ?? '');
    $published = isset($_POST['is_published']) ? 1 : 0;

    if (!empty($title)) {
        if ($id > 0) {
            $db->prepare("UPDATE pages SET title=?, slug=?, content=?, meta_title=?, meta_description=?, is_published=? WHERE id=?")
               ->execute([$title, $slug, $content, $metaTitle, $metaDesc, $published, $id]);
        } else {
            $db->prepare("INSERT INTO pages (title, slug, content, meta_title, meta_description, is_published) VALUES (?,?,?,?,?,?)")
               ->execute([$title, $slug, $content, $metaTitle, $metaDesc, $published]);
            $id = $db->lastInsertId();
        }
        $success = 'تم الحفظ بنجاح';
        $stmt = $db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $page = $stmt->fetch();
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-file-alt me-2"></i> <?= $id ? 'تعديل' : 'إضافة' ?> صفحة</h4>
    <a href="/admin/pages" class="btn btn-secondary"><i class="fas fa-arrow-right me-1"></i> العودة</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<form method="POST">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-form">
                <div class="mb-3"><label class="form-label">العنوان *</label><input type="text" name="title" class="form-control" value="<?= clean($page['title'] ?? '') ?>" required></div>
                <div class="mb-3"><label class="form-label">الرابط</label><input type="text" name="slug" class="form-control" value="<?= clean($page['slug'] ?? '') ?>" dir="ltr"></div>
                <div class="mb-3"><label class="form-label">المحتوى (HTML)</label><textarea name="content" class="form-control" rows="15"><?= $page['content'] ?? '' ?></textarea></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="admin-form">
                <div class="mb-3"><label class="form-label">عنوان SEO</label><input type="text" name="meta_title" class="form-control" value="<?= clean($page['meta_title'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label">وصف SEO</label><textarea name="meta_description" class="form-control" rows="3"><?= clean($page['meta_description'] ?? '') ?></textarea></div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="is_published" class="form-check-input" id="pub" <?= ($page['is_published'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="pub">منشور</label>
                </div>
                <button type="submit" class="btn btn-admin-primary w-100"><i class="fas fa-save me-2"></i> حفظ</button>
            </div>
        </div>
    </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
