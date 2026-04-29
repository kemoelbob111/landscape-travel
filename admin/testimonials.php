<?php
/**
 * Admin Testimonials
 */
$adminPageTitle = 'إدارة الشهادات';
$db = getDB();
$success = '';

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM testimonials WHERE id = ?")->execute([$_GET['delete']]);
    redirect('/admin/testimonials');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $rating = intval($_POST['rating'] ?? 5);
    $order = intval($_POST['sort_order'] ?? 0);
    $active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($name) && !empty($content)) {
        if ($id > 0) {
            $db->prepare("UPDATE testimonials SET name=?, title=?, content=?, rating=?, sort_order=?, is_active=? WHERE id=?")
               ->execute([$name, $title, $content, $rating, $order, $active, $id]);
        } else {
            $db->prepare("INSERT INTO testimonials (name, title, content, rating, sort_order, is_active) VALUES (?,?,?,?,?,?)")
               ->execute([$name, $title, $content, $rating, $order, $active]);
        }
        $success = 'تم الحفظ بنجاح';
    }
}

$testimonials = $db->query("SELECT * FROM testimonials ORDER BY sort_order ASC")->fetchAll();
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-star me-2"></i> إدارة الشهادات</h4>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#testModal" onclick="document.getElementById('testForm').reset(); document.getElementById('testId').value=0;">
        <i class="fas fa-plus me-1"></i> إضافة شهادة
    </button>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>الاسم</th><th>المحتوى</th><th>التقييم</th><th>الحالة</th><th>إجراءات</th></tr></thead>
            <tbody>
                <?php foreach ($testimonials as $t): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td><?= clean($t['name']) ?></td>
                    <td><?= clean(mb_substr($t['content'], 0, 50)) ?>...</td>
                    <td><?php for($i=0;$i<$t['rating'];$i++) echo '<i class="fas fa-star text-warning"></i>'; ?></td>
                    <td><?= $t['is_active'] ? '<span class="badge bg-success">نشط</span>' : '<span class="badge bg-secondary">معطل</span>' ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editTest(<?= htmlspecialchars(json_encode($t)) ?>)"><i class="fas fa-edit"></i></button>
                        <a href="/admin/testimonials?delete=<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="testModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="testForm">
                <input type="hidden" name="id" id="testId" value="0">
                <div class="modal-header"><h5 class="modal-title">شهادة عميل</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">الاسم</label><input type="text" name="name" id="testName" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">اللقب</label><input type="text" name="title" id="testTitle" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">المحتوى</label><textarea name="content" id="testContent" class="form-control" rows="3" required></textarea></div>
                    <div class="mb-3"><label class="form-label">التقييم</label><select name="rating" id="testRating" class="form-select"><option value="5">5</option><option value="4">4</option><option value="3">3</option><option value="2">2</option><option value="1">1</option></select></div>
                    <div class="mb-3"><label class="form-label">الترتيب</label><input type="number" name="sort_order" id="testOrder" class="form-control" value="0"></div>
                    <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input" id="testActive" checked><label class="form-check-label" for="testActive">نشط</label></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-admin-primary">حفظ</button></div>
            </form>
        </div>
    </div>
</div>

<script>
function editTest(t) {
    document.getElementById('testId').value = t.id;
    document.getElementById('testName').value = t.name;
    document.getElementById('testTitle').value = t.title || '';
    document.getElementById('testContent').value = t.content;
    document.getElementById('testRating').value = t.rating;
    document.getElementById('testOrder').value = t.sort_order;
    document.getElementById('testActive').checked = t.is_active == 1;
    new bootstrap.Modal(document.getElementById('testModal')).show();
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
