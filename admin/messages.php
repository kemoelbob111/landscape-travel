<?php
/**
 * Admin Messages
 */
$adminPageTitle = 'الرسائل';
$db = getDB();

if (isset($_GET['read'])) {
    $db->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?")->execute([$_GET['read']]);
    redirect('/admin/messages');
}

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$_GET['delete']]);
    redirect('/admin/messages');
}

$messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-envelope me-2"></i> الرسائل</h4>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>#</th><th>الاسم</th><th>البريد</th><th>الموضوع</th><th>الحالة</th><th>التاريخ</th><th>إجراءات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $m): ?>
                <tr class="<?= !$m['is_read'] ? 'fw-bold' : '' ?>">
                    <td><?= $m['id'] ?></td>
                    <td><?= clean($m['name']) ?></td>
                    <td><?= clean($m['email'] ?? '-') ?></td>
                    <td><?= clean(mb_substr($m['subject'] ?? '-', 0, 30)) ?></td>
                    <td><?= $m['is_read'] ? '<span class="badge bg-secondary">مقروء</span>' : '<span class="badge bg-primary">جديد</span>' ?></td>
                    <td><?= date('Y/m/d H:i', strtotime($m['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#msgModal<?= $m['id'] ?>"><i class="fas fa-eye"></i></button>
                        <?php if (!$m['is_read']): ?>
                            <a href="/admin/messages?read=<?= $m['id'] ?>" class="btn btn-sm btn-outline-success me-1"><i class="fas fa-check"></i></a>
                        <?php endif; ?>
                        <a href="/admin/messages?delete=<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <div class="modal fade" id="msgModal<?= $m['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">رسالة من <?= clean($m['name']) ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body">
                                <p><strong>الاسم:</strong> <?= clean($m['name']) ?></p>
                                <p><strong>البريد:</strong> <?= clean($m['email'] ?? '-') ?></p>
                                <p><strong>الهاتف:</strong> <?= clean($m['phone'] ?? '-') ?></p>
                                <p><strong>الموضوع:</strong> <?= clean($m['subject'] ?? '-') ?></p>
                                <hr>
                                <p><?= nl2br(clean($m['message'])) ?></p>
                                <small class="text-muted"><?= $m['created_at'] ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
