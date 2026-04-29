<?php
/**
 * Admin Menus
 */
$adminPageTitle = 'إدارة القوائم';
$db = getDB();
$success = '';

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM menus WHERE id = ?")->execute([$_GET['delete']]);
    redirect('/admin/menus');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $order = intval($_POST['sort_order'] ?? 0);
    $active = isset($_POST['is_active']) ? 1 : 0;
    $target = $_POST['target'] ?? '_self';

    if (!empty($title) && !empty($url)) {
        if ($id > 0) {
            $db->prepare("UPDATE menus SET title=?, url=?, sort_order=?, is_active=?, target=? WHERE id=?")->execute([$title, $url, $order, $active, $target, $id]);
        } else {
            $db->prepare("INSERT INTO menus (title, url, sort_order, is_active, target) VALUES (?,?,?,?,?)")->execute([$title, $url, $order, $active, $target]);
        }
        $success = 'تم الحفظ بنجاح';
    }
}

$menus = $db->query("SELECT * FROM menus ORDER BY sort_order ASC")->fetchAll();
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-bars me-2"></i> إدارة القوائم</h4>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#menuModal" onclick="document.getElementById('menuForm').reset(); document.getElementById('menuId').value=0;">
        <i class="fas fa-plus me-1"></i> إضافة رابط
    </button>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>الترتيب</th><th>العنوان</th><th>الرابط</th><th>الحالة</th><th>إجراءات</th></tr></thead>
            <tbody>
                <?php foreach ($menus as $m): ?>
                <tr>
                    <td><?= $m['sort_order'] ?></td>
                    <td><?= clean($m['title']) ?></td>
                    <td dir="ltr"><?= clean($m['url']) ?></td>
                    <td><?= $m['is_active'] ? '<span class="badge bg-success">نشط</span>' : '<span class="badge bg-secondary">معطل</span>' ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editMenu(<?= htmlspecialchars(json_encode($m)) ?>)"><i class="fas fa-edit"></i></button>
                        <a href="/admin/menus?delete=<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="menuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="menuForm">
                <input type="hidden" name="id" id="menuId" value="0">
                <div class="modal-header"><h5 class="modal-title">رابط القائمة</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">العنوان</label><input type="text" name="title" id="menuTitle" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">الرابط (URL)</label><input type="text" name="url" id="menuUrl" class="form-control" dir="ltr" required></div>
                    <div class="mb-3"><label class="form-label">الترتيب</label><input type="number" name="sort_order" id="menuOrder" class="form-control" value="0"></div>
                    <div class="mb-3"><label class="form-label">فتح في</label>
                        <select name="target" id="menuTarget" class="form-select">
                            <option value="_self">نفس الصفحة</option>
                            <option value="_blank">نافذة جديدة</option>
                        </select>
                    </div>
                    <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input" id="menuActive" checked><label class="form-check-label" for="menuActive">نشط</label></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-admin-primary">حفظ</button></div>
            </form>
        </div>
    </div>
</div>

<script>
function editMenu(m) {
    document.getElementById('menuId').value = m.id;
    document.getElementById('menuTitle').value = m.title;
    document.getElementById('menuUrl').value = m.url;
    document.getElementById('menuOrder').value = m.sort_order;
    document.getElementById('menuTarget').value = m.target || '_self';
    document.getElementById('menuActive').checked = m.is_active == 1;
    new bootstrap.Modal(document.getElementById('menuModal')).show();
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
