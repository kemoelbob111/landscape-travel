<?php
/**
 * Admin Programs List
 */
$adminPageTitle = 'إدارة البرامج';
$db = getDB();

// Handle delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM programs WHERE id = ?")->execute([$_GET['delete']]);
    redirect('/admin/programs');
}

// Handle toggle status
if (isset($_GET['toggle'])) {
    $db->prepare("UPDATE programs SET is_active = NOT is_active WHERE id = ?")->execute([$_GET['toggle']]);
    redirect('/admin/programs');
}

$programs = $db->query("SELECT p.*, c.name as category_name FROM programs p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.type, p.sort_order ASC")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-suitcase me-2"></i> إدارة البرامج</h4>
    <a href="/admin/program-edit" class="btn btn-admin-primary">
        <i class="fas fa-plus me-1"></i> إضافة برنامج
    </a>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>العنوان</th>
                    <th>النوع</th>
                    <th>التصنيف</th>
                    <th>السعر</th>
                    <th>المدة</th>
                    <th>مميز</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($programs as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= clean(mb_substr($p['title'], 0, 40)) ?></td>
                    <td><span class="badge bg-<?= $p['type'] === 'hajj' ? 'danger' : 'primary' ?>"><?= $p['type'] === 'hajj' ? 'حج' : 'عمرة' ?></span></td>
                    <td><?= clean($p['category_name'] ?? '-') ?></td>
                    <td><?= formatPrice($p['price']) ?></td>
                    <td><?= clean($p['duration']) ?></td>
                    <td><?= $p['is_featured'] ? '<i class="fas fa-star text-warning"></i>' : '-' ?></td>
                    <td>
                        <a href="/admin/programs?toggle=<?= $p['id'] ?>" class="badge bg-<?= $p['is_active'] ? 'success' : 'secondary' ?> text-decoration-none">
                            <?= $p['is_active'] ? 'نشط' : 'معطل' ?>
                        </a>
                    </td>
                    <td>
                        <a href="/admin/program-edit?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
                        <a href="/admin/programs?delete=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
