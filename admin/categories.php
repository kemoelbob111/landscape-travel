<?php
/**
 * Admin Categories
 */
$adminPageTitle = 'إدارة التصنيفات';
$db = getDB();
$success = '';

// Handle delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM categories WHERE id = ?")->execute([$_GET['delete']]);
    redirect('/admin/categories');
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: generateSlug($name);
    $type = $_POST['type'] ?? 'umrah';
    $desc = trim($_POST['description'] ?? '');
    $order = intval($_POST['sort_order'] ?? 0);

    if (!empty($name)) {
        if ($id > 0) {
            $db->prepare("UPDATE categories SET name=?, slug=?, type=?, description=?, sort_order=? WHERE id=?")->execute([$name, $slug, $type, $desc, $order, $id]);
        } else {
            $db->prepare("INSERT INTO categories (name, slug, type, description, sort_order) VALUES (?,?,?,?,?)")->execute([$name, $slug, $type, $desc, $order]);
        }
        $success = 'تم الحفظ بنجاح';
    }
}

$categories = $db->query("SELECT * FROM categories ORDER BY type, sort_order")->fetchAll();
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-folder me-2"></i> إدارة التصنيفات</h4>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#catModal" onclick="document.getElementById('catForm').reset(); document.getElementById('catId').value=0;">
        <i class="fas fa-plus me-1"></i> إضافة تصنيف
    </button>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>#</th><th>الاسم</th><th>الرابط</th><th>النوع</th><th>الترتيب</th><th>إجراءات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= clean($c['name']) ?></td>
                    <td dir="ltr"><?= clean($c['slug']) ?></td>
                    <td><span class="badge bg-<?= $c['type'] === 'hajj' ? 'danger' : ($c['type'] === 'umrah' ? 'primary' : 'info') ?>"><?= $c['type'] ?></span></td>
                    <td><?= $c['sort_order'] ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editCat(<?= htmlspecialchars(json_encode($c)) ?>)"><i class="fas fa-edit"></i></button>
                        <a href="/admin/categories?delete=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="catModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="catForm">
                <input type="hidden" name="id" id="catId" value="0">
                <div class="modal-header"><h5 class="modal-title">تصنيف</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">الاسم</label><input type="text" name="name" id="catName" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">الرابط</label><input type="text" name="slug" id="catSlug" class="form-control" dir="ltr"></div>
                    <div class="mb-3"><label class="form-label">النوع</label>
                        <select name="type" id="catType" class="form-select">
                            <option value="umrah">عمرة</option><option value="hajj">حج</option><option value="blog">مدونة</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">الوصف</label><textarea name="description" id="catDesc" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">الترتيب</label><input type="number" name="sort_order" id="catOrder" class="form-control" value="0"></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-admin-primary">حفظ</button></div>
            </form>
        </div>
    </div>
</div>

<script>
function editCat(c) {
    document.getElementById('catId').value = c.id;
    document.getElementById('catName').value = c.name;
    document.getElementById('catSlug').value = c.slug;
    document.getElementById('catType').value = c.type;
    document.getElementById('catDesc').value = c.description || '';
    document.getElementById('catOrder').value = c.sort_order;
    new bootstrap.Modal(document.getElementById('catModal')).show();
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
