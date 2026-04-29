<?php
/**
 * Admin Banners
 */
$adminPageTitle = 'إدارة البانرات';
$db = getDB();
$success = '';

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM banners WHERE id = ?")->execute([$_GET['delete']]);
    redirect('/admin/banners');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $buttonText = trim($_POST['button_text'] ?? '');
    $order = intval($_POST['sort_order'] ?? 0);
    $active = isset($_POST['is_active']) ? 1 : 0;

    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp'])) {
            $filename = 'banner_' . time() . '.' . $ext;
            $imagePath = 'uploads/banners/' . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../' . $imagePath);
        }
    }

    if ($id > 0) {
        $sql = "UPDATE banners SET title=?, subtitle=?, link=?, button_text=?, sort_order=?, is_active=?";
        $params = [$title, $subtitle, $link, $buttonText, $order, $active];
        if ($imagePath) { $sql .= ", image=?"; $params[] = $imagePath; }
        $sql .= " WHERE id=?";
        $params[] = $id;
        $db->prepare($sql)->execute($params);
    } else {
        if (!$imagePath) $imagePath = 'assets/images/banner1.jpg';
        $db->prepare("INSERT INTO banners (title, subtitle, image, link, button_text, sort_order, is_active) VALUES (?,?,?,?,?,?,?)")
           ->execute([$title, $subtitle, $imagePath, $link, $buttonText, $order, $active]);
    }
    $success = 'تم الحفظ بنجاح';
}

$banners = $db->query("SELECT * FROM banners ORDER BY sort_order ASC")->fetchAll();
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-images me-2"></i> إدارة البانرات</h4>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#bannerModal" onclick="document.getElementById('bannerForm').reset(); document.getElementById('bannerId').value=0;">
        <i class="fas fa-plus me-1"></i> إضافة بانر
    </button>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row g-4">
    <?php foreach ($banners as $b): ?>
    <div class="col-md-6">
        <div class="admin-form position-relative">
            <div style="height:150px; border-radius:8px; overflow:hidden; margin-bottom:15px;">
                <img src="/<?= clean($b['image']) ?>" style="width:100%;height:100%;object-fit:cover;" alt="">
            </div>
            <h6><?= clean($b['title']) ?></h6>
            <p class="text-muted small"><?= clean($b['subtitle']) ?></p>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="editBanner(<?= htmlspecialchars(json_encode($b)) ?>)"><i class="fas fa-edit me-1"></i>تعديل</button>
                <a href="/admin/banners?delete=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash me-1"></i>حذف</a>
                <span class="badge bg-<?= $b['is_active'] ? 'success' : 'secondary' ?> align-self-center"><?= $b['is_active'] ? 'نشط' : 'معطل' ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="modal fade" id="bannerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" id="bannerForm">
                <input type="hidden" name="id" id="bannerId" value="0">
                <div class="modal-header"><h5 class="modal-title">بانر</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">العنوان</label><input type="text" name="title" id="bannerTitle" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">العنوان الفرعي</label><input type="text" name="subtitle" id="bannerSubtitle" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">الصورة</label><input type="file" name="image" class="form-control" accept="image/*"></div>
                    <div class="mb-3"><label class="form-label">الرابط</label><input type="text" name="link" id="bannerLink" class="form-control" dir="ltr"></div>
                    <div class="mb-3"><label class="form-label">نص الزر</label><input type="text" name="button_text" id="bannerBtn" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">الترتيب</label><input type="number" name="sort_order" id="bannerOrder" class="form-control" value="0"></div>
                    <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input" id="bannerActive" checked><label class="form-check-label" for="bannerActive">نشط</label></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-admin-primary">حفظ</button></div>
            </form>
        </div>
    </div>
</div>

<script>
function editBanner(b) {
    document.getElementById('bannerId').value = b.id;
    document.getElementById('bannerTitle').value = b.title || '';
    document.getElementById('bannerSubtitle').value = b.subtitle || '';
    document.getElementById('bannerLink').value = b.link || '';
    document.getElementById('bannerBtn').value = b.button_text || '';
    document.getElementById('bannerOrder').value = b.sort_order;
    document.getElementById('bannerActive').checked = b.is_active == 1;
    new bootstrap.Modal(document.getElementById('bannerModal')).show();
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
