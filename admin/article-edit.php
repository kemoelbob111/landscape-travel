<?php
/**
 * Admin Article Add/Edit
 */
$adminPageTitle = 'إدارة المقال';
$db = getDB();
$id = intval($_GET['id'] ?? 0);
$article = null;
$success = '';

if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch();
}

$categories = $db->query("SELECT * FROM categories WHERE type = 'blog' AND is_active = 1 ORDER BY sort_order")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'slug' => trim($_POST['slug'] ?? '') ?: generateSlug($_POST['title'] ?? ''),
        'category_id' => intval($_POST['category_id'] ?? 0) ?: null,
        'excerpt' => trim($_POST['excerpt'] ?? ''),
        'content' => $_POST['content'] ?? '',
        'author' => trim($_POST['author'] ?? ''),
        'meta_title' => trim($_POST['meta_title'] ?? ''),
        'meta_description' => trim($_POST['meta_description'] ?? ''),
        'is_published' => isset($_POST['is_published']) ? 1 : 0,
    ];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp'])) {
            $filename = 'article_' . time() . '.' . $ext;
            $path = 'uploads/blog/' . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../' . $path);
            $data['image'] = $path;
        }
    }

    if (!empty($data['title'])) {
        if ($id > 0) {
            $fields = []; $values = [];
            foreach ($data as $k => $v) { $fields[] = "$k = ?"; $values[] = $v; }
            $values[] = $id;
            $db->prepare("UPDATE articles SET " . implode(', ', $fields) . " WHERE id = ?")->execute($values);
        } else {
            if (!isset($data['image'])) $data['image'] = '';
            $fields = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            $db->prepare("INSERT INTO articles ($fields) VALUES ($placeholders)")->execute(array_values($data));
            $id = $db->lastInsertId();
        }
        $success = 'تم الحفظ بنجاح';
        $stmt = $db->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $article = $stmt->fetch();
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-newspaper me-2"></i> <?= $id ? 'تعديل' : 'إضافة' ?> مقال</h4>
    <a href="/admin/articles" class="btn btn-secondary"><i class="fas fa-arrow-right me-1"></i> العودة</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-form">
                <div class="mb-3"><label class="form-label">العنوان *</label><input type="text" name="title" class="form-control" value="<?= clean($article['title'] ?? '') ?>" required></div>
                <div class="mb-3"><label class="form-label">الرابط</label><input type="text" name="slug" class="form-control" value="<?= clean($article['slug'] ?? '') ?>" dir="ltr"></div>
                <div class="mb-3"><label class="form-label">المقتطف</label><textarea name="excerpt" class="form-control" rows="2"><?= clean($article['excerpt'] ?? '') ?></textarea></div>
                <div class="mb-3"><label class="form-label">المحتوى</label><textarea name="content" class="form-control" rows="12"><?= $article['content'] ?? '' ?></textarea></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="admin-form mb-4">
                <div class="mb-3"><label class="form-label">التصنيف</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- بدون --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($article['category_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>><?= clean($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">الكاتب</label><input type="text" name="author" class="form-control" value="<?= clean($article['author'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label">الصورة</label><input type="file" name="image" class="form-control" accept="image/*">
                    <?php if (!empty($article['image'])): ?><small class="text-muted"><?= clean($article['image']) ?></small><?php endif; ?>
                </div>
                <div class="mb-3"><label class="form-label">عنوان SEO</label><input type="text" name="meta_title" class="form-control" value="<?= clean($article['meta_title'] ?? '') ?>"></div>
                <div class="mb-3"><label class="form-label">وصف SEO</label><textarea name="meta_description" class="form-control" rows="2"><?= clean($article['meta_description'] ?? '') ?></textarea></div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="is_published" class="form-check-input" id="pub" <?= ($article['is_published'] ?? 0) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="pub">منشور</label>
                </div>
                <button type="submit" class="btn btn-admin-primary w-100"><i class="fas fa-save me-2"></i> حفظ</button>
            </div>
        </div>
    </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
