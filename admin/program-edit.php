<?php
/**
 * Admin Program Add/Edit
 */
$adminPageTitle = 'إدارة البرنامج';
$db = getDB();
$id = intval($_GET['id'] ?? 0);
$program = null;
$success = '';
$error = '';

if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM programs WHERE id = ?");
    $stmt->execute([$id]);
    $program = $stmt->fetch();
}

$categories = $db->query("SELECT * FROM categories WHERE type IN ('hajj','umrah') AND is_active = 1 ORDER BY type, sort_order")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'slug' => trim($_POST['slug'] ?? '') ?: generateSlug($_POST['title'] ?? ''),
        'category_id' => intval($_POST['category_id'] ?? 0) ?: null,
        'type' => $_POST['type'] ?? 'umrah',
        'duration' => trim($_POST['duration'] ?? ''),
        'price' => floatval($_POST['price'] ?? 0),
        'price_note' => trim($_POST['price_note'] ?? ''),
        'short_description' => trim($_POST['short_description'] ?? ''),
        'description' => $_POST['description'] ?? '',
        'itinerary' => $_POST['itinerary'] ?? '',
        'hotels' => $_POST['hotels'] ?? '',
        'features' => $_POST['features'] ?? '',
        'includes' => $_POST['includes'] ?? '',
        'excludes' => $_POST['excludes'] ?? '',
        'terms' => $_POST['terms'] ?? '',
        'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'sort_order' => intval($_POST['sort_order'] ?? 0),
    ];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (in_array(strtolower($ext), $allowed)) {
            $filename = 'program_' . time() . '.' . $ext;
            $path = 'uploads/programs/' . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../' . $path);
            $data['image'] = $path;
        }
    }

    if (empty($data['title'])) {
        $error = 'عنوان البرنامج مطلوب';
    } else {
        try {
            if ($id > 0) {
                $fields = [];
                $values = [];
                foreach ($data as $key => $val) {
                    $fields[] = "$key = ?";
                    $values[] = $val;
                }
                $values[] = $id;
                $db->prepare("UPDATE programs SET " . implode(', ', $fields) . " WHERE id = ?")->execute($values);
                $success = 'تم تحديث البرنامج بنجاح';
            } else {
                if (!isset($data['image'])) $data['image'] = '';
                $fields = implode(', ', array_keys($data));
                $placeholders = implode(', ', array_fill(0, count($data), '?'));
                $db->prepare("INSERT INTO programs ($fields) VALUES ($placeholders)")->execute(array_values($data));
                $id = $db->lastInsertId();
                $success = 'تم إضافة البرنامج بنجاح';
            }
            // Refresh data
            $stmt = $db->prepare("SELECT * FROM programs WHERE id = ?");
            $stmt->execute([$id]);
            $program = $stmt->fetch();
        } catch (PDOException $e) {
            $error = 'حدث خطأ: ' . $e->getMessage();
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-suitcase me-2"></i> <?= $id ? 'تعديل' : 'إضافة' ?> برنامج</h4>
    <a href="/admin/programs" class="btn btn-secondary"><i class="fas fa-arrow-right me-1"></i> العودة</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="admin-form mb-4">
                <h5 class="mb-4 fw-bold">معلومات أساسية</h5>
                <div class="mb-3">
                    <label class="form-label">العنوان *</label>
                    <input type="text" name="title" class="form-control" value="<?= clean($program['title'] ?? '') ?>" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الرابط (Slug)</label>
                        <input type="text" name="slug" class="form-control" value="<?= clean($program['slug'] ?? '') ?>" dir="ltr">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">النوع</label>
                        <select name="type" class="form-select">
                            <option value="umrah" <?= ($program['type'] ?? '') === 'umrah' ? 'selected' : '' ?>>عمرة</option>
                            <option value="hajj" <?= ($program['type'] ?? '') === 'hajj' ? 'selected' : '' ?>>حج</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <label class="form-label">التصنيف</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- بدون تصنيف --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($program['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                                    <?= clean($cat['name']) ?> (<?= $cat['type'] === 'hajj' ? 'حج' : 'عمرة' ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">السعر (ر.س)</label>
                        <input type="number" name="price" class="form-control" value="<?= $program['price'] ?? 0 ?>" step="0.01">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">المدة</label>
                        <input type="text" name="duration" class="form-control" value="<?= clean($program['duration'] ?? '') ?>">
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <label class="form-label">ملاحظة السعر</label>
                    <input type="text" name="price_note" class="form-control" value="<?= clean($program['price_note'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">وصف مختصر</label>
                    <textarea name="short_description" class="form-control" rows="2"><?= clean($program['short_description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="admin-form mb-4">
                <h5 class="mb-4 fw-bold">محتوى تفصيلي</h5>
                <div class="mb-3">
                    <label class="form-label">الوصف الكامل</label>
                    <textarea name="description" class="form-control" rows="6"><?= $program['description'] ?? '' ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">البرنامج اليومي</label>
                    <textarea name="itinerary" class="form-control" rows="5"><?= $program['itinerary'] ?? '' ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">الفنادق</label>
                    <textarea name="hotels" class="form-control" rows="3"><?= $program['hotels'] ?? '' ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">المميزات</label>
                    <textarea name="features" class="form-control" rows="3"><?= $program['features'] ?? '' ?></textarea>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">يشمل البرنامج</label>
                        <textarea name="includes" class="form-control" rows="4"><?= $program['includes'] ?? '' ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">لا يشمل البرنامج</label>
                        <textarea name="excludes" class="form-control" rows="4"><?= $program['excludes'] ?? '' ?></textarea>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <label class="form-label">الشروط والأحكام</label>
                    <textarea name="terms" class="form-control" rows="3"><?= $program['terms'] ?? '' ?></textarea>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-form mb-4">
                <h5 class="mb-4 fw-bold">إعدادات</h5>
                <div class="mb-3">
                    <label class="form-label">صورة البرنامج</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <?php if (!empty($program['image'])): ?>
                        <small class="text-muted d-block mt-1">الحالي: <?= clean($program['image']) ?></small>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label">ترتيب العرض</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $program['sort_order'] ?? 0 ?>">
                </div>
                <div class="form-check mb-2">
                    <input type="checkbox" name="is_featured" class="form-check-input" id="featured" <?= ($program['is_featured'] ?? 0) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="featured">برنامج مميز</label>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="is_active" class="form-check-input" id="active" <?= ($program['is_active'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="active">نشط</label>
                </div>
                <button type="submit" class="btn btn-admin-primary w-100">
                    <i class="fas fa-save me-2"></i> <?= $id ? 'تحديث' : 'إضافة' ?> البرنامج
                </button>
            </div>
        </div>
    </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
