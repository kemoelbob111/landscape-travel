<?php
/**
 * Admin Users
 */
$adminPageTitle = 'إدارة المستخدمين';
$db = getDB();
$success = '';
$error = '';

if (isset($_GET['delete']) && $_GET['delete'] != $_SESSION['admin_id']) {
    $db->prepare("DELETE FROM users WHERE id = ?")->execute([$_GET['delete']]);
    redirect('/admin/users');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'admin';
    $password = $_POST['password'] ?? '';
    $active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($username) && !empty($name)) {
        if ($id > 0) {
            $sql = "UPDATE users SET username=?, name=?, email=?, role=?, is_active=?";
            $params = [$username, $name, $email, $role, $active];
            if (!empty($password)) {
                $sql .= ", password=?";
                $params[] = password_hash($password, PASSWORD_DEFAULT);
            }
            $sql .= " WHERE id=?";
            $params[] = $id;
            $db->prepare($sql)->execute($params);
        } else {
            if (empty($password)) {
                $error = 'كلمة المرور مطلوبة';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $db->prepare("INSERT INTO users (username, password, name, email, role, is_active) VALUES (?,?,?,?,?,?)")
                   ->execute([$username, $hash, $name, $email, $role, $active]);
            }
        }
        if (!$error) $success = 'تم الحفظ بنجاح';
    }
}

$users = $db->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();
$roles = ['super_admin' => 'مدير عام', 'admin' => 'مدير', 'editor' => 'محرر'];
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-users-cog me-2"></i> إدارة المستخدمين</h4>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="document.getElementById('userForm').reset(); document.getElementById('userId').value=0;">
        <i class="fas fa-plus me-1"></i> إضافة مستخدم
    </button>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?= $error ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>#</th><th>المستخدم</th><th>الاسم</th><th>البريد</th><th>الدور</th><th>الحالة</th><th>آخر دخول</th><th>إجراءات</th></tr></thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= clean($u['username']) ?></td>
                    <td><?= clean($u['name']) ?></td>
                    <td><?= clean($u['email'] ?? '-') ?></td>
                    <td><?= $roles[$u['role']] ?? $u['role'] ?></td>
                    <td><?= $u['is_active'] ? '<span class="badge bg-success">نشط</span>' : '<span class="badge bg-secondary">معطل</span>' ?></td>
                    <td><?= $u['last_login'] ? date('Y/m/d H:i', strtotime($u['last_login'])) : '-' ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="editUser(<?= htmlspecialchars(json_encode($u)) ?>)"><i class="fas fa-edit"></i></button>
                        <?php if ($u['id'] != $_SESSION['admin_id']): ?>
                            <a href="/admin/users?delete=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="userForm">
                <input type="hidden" name="id" id="userId" value="0">
                <div class="modal-header"><h5 class="modal-title">مستخدم</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">اسم المستخدم</label><input type="text" name="username" id="userUsername" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">الاسم الكامل</label><input type="text" name="name" id="userName" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">البريد</label><input type="email" name="email" id="userEmail" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">كلمة المرور <small class="text-muted">(اتركها فارغة للإبقاء)</small></label><input type="password" name="password" id="userPass" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">الدور</label>
                        <select name="role" id="userRole" class="form-select">
                            <option value="super_admin">مدير عام</option>
                            <option value="admin">مدير</option>
                            <option value="editor">محرر</option>
                        </select>
                    </div>
                    <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input" id="userActive" checked><label class="form-check-label" for="userActive">نشط</label></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-admin-primary">حفظ</button></div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(u) {
    document.getElementById('userId').value = u.id;
    document.getElementById('userUsername').value = u.username;
    document.getElementById('userName').value = u.name;
    document.getElementById('userEmail').value = u.email || '';
    document.getElementById('userPass').value = '';
    document.getElementById('userRole').value = u.role;
    document.getElementById('userActive').checked = u.is_active == 1;
    new bootstrap.Modal(document.getElementById('userModal')).show();
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
