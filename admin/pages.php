<?php
/**
 * Admin Pages
 */
$adminPageTitle = 'إدارة الصفحات';
$db = getDB();

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM pages WHERE id = ?")->execute([$_GET['delete']]);
    redirect('/admin/pages');
}

if (isset($_GET['toggle'])) {
    $db->prepare("UPDATE pages SET is_published = NOT is_published WHERE id = ?")->execute([$_GET['toggle']]);
    redirect('/admin/pages');
}

$pages = $db->query("SELECT * FROM pages ORDER BY created_at DESC")->fetchAll();
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-file-alt me-2"></i> إدارة الصفحات</h4>
    <a href="/admin/page-edit" class="btn btn-admin-primary"><i class="fas fa-plus me-1"></i> إضافة صفحة</a>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>العنوان</th><th>الرابط</th><th>الحالة</th><th>التاريخ</th><th>إجراءات</th></tr></thead>
            <tbody>
                <?php foreach ($pages as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= clean($p['title']) ?></td>
                    <td dir="ltr">/<?= clean($p['slug']) ?></td>
                    <td>
                        <a href="/admin/pages?toggle=<?= $p['id'] ?>" class="badge bg-<?= $p['is_published'] ? 'success' : 'secondary' ?> text-decoration-none">
                            <?= $p['is_published'] ? 'منشور' : 'مسودة' ?>
                        </a>
                    </td>
                    <td><?= date('Y/m/d', strtotime($p['created_at'])) ?></td>
                    <td>
                        <a href="/admin/page-edit?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                        <a href="/admin/pages?delete=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
