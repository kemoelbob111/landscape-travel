<?php
/**
 * Admin Articles
 */
$adminPageTitle = 'إدارة المدونة';
$db = getDB();

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM articles WHERE id = ?")->execute([$_GET['delete']]);
    redirect('/admin/articles');
}

if (isset($_GET['toggle'])) {
    $db->prepare("UPDATE articles SET is_published = NOT is_published WHERE id = ?")->execute([$_GET['toggle']]);
    redirect('/admin/articles');
}

$articles = $db->query("SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id ORDER BY a.created_at DESC")->fetchAll();
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-newspaper me-2"></i> إدارة المدونة</h4>
    <a href="/admin/article-edit" class="btn btn-admin-primary"><i class="fas fa-plus me-1"></i> إضافة مقال</a>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>#</th><th>العنوان</th><th>التصنيف</th><th>الحالة</th><th>المشاهدات</th><th>التاريخ</th><th>إجراءات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= clean(mb_substr($a['title'], 0, 40)) ?></td>
                    <td><?= clean($a['category_name'] ?? '-') ?></td>
                    <td>
                        <a href="/admin/articles?toggle=<?= $a['id'] ?>" class="badge bg-<?= $a['is_published'] ? 'success' : 'secondary' ?> text-decoration-none">
                            <?= $a['is_published'] ? 'منشور' : 'مسودة' ?>
                        </a>
                    </td>
                    <td><?= $a['views'] ?></td>
                    <td><?= date('Y/m/d', strtotime($a['created_at'])) ?></td>
                    <td>
                        <a href="/admin/article-edit?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                        <a href="/admin/articles?delete=<?= $a['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
