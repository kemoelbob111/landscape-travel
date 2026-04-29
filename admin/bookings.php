<?php
/**
 * Admin Bookings
 */
$adminPageTitle = 'إدارة الحجوزات';
$db = getDB();

// Handle status update
if (isset($_POST['update_status'])) {
    $db->prepare("UPDATE bookings SET status = ?, admin_notes = ? WHERE id = ?")
       ->execute([$_POST['status'], $_POST['admin_notes'] ?? '', $_POST['booking_id']]);
    redirect('/admin/bookings');
}

// Handle CSV export
if (isset($_GET['export'])) {
    $bookings = $db->query("SELECT * FROM bookings ORDER BY created_at DESC")->fetchAll();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=bookings_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for Excel
    fputcsv($output, ['ID', 'الاسم', 'الهاتف', 'واتساب', 'البريد', 'البرنامج', 'عدد الأفراد', 'الحالة', 'ملاحظات', 'التاريخ']);
    foreach ($bookings as $b) {
        fputcsv($output, [$b['id'], $b['name'], $b['phone'], $b['whatsapp'], $b['email'], $b['program_title'], $b['persons'], $b['status'], $b['notes'], $b['created_at']]);
    }
    fclose($output);
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM bookings WHERE id = ?")->execute([$_GET['delete']]);
    redirect('/admin/bookings');
}

$bookings = $db->query("SELECT * FROM bookings ORDER BY created_at DESC")->fetchAll();
$statusLabels = ['new' => 'جديد', 'contacted' => 'تم التواصل', 'confirmed' => 'مؤكد', 'cancelled' => 'ملغي'];

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-calendar-check me-2"></i> إدارة الحجوزات</h4>
    <a href="/admin/bookings?export=1" class="btn btn-success"><i class="fas fa-file-csv me-1"></i> تصدير CSV</a>
</div>

<div class="admin-table">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>#</th><th>الاسم</th><th>الهاتف</th><th>البرنامج</th><th>عدد</th><th>الحالة</th><th>التاريخ</th><th>إجراءات</th></tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr>
                    <td><?= $b['id'] ?></td>
                    <td><?= clean($b['name']) ?></td>
                    <td dir="ltr"><?= clean($b['phone']) ?></td>
                    <td><?= clean(mb_substr($b['program_title'] ?? '-', 0, 30)) ?></td>
                    <td><?= $b['persons'] ?></td>
                    <td><span class="badge-status badge-<?= $b['status'] ?>"><?= $statusLabels[$b['status']] ?? $b['status'] ?></span></td>
                    <td><?= date('Y/m/d H:i', strtotime($b['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#bookingModal<?= $b['id'] ?>"><i class="fas fa-eye"></i></button>
                        <a href="/admin/bookings?delete=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف؟')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>

                <!-- Booking Detail Modal -->
                <div class="modal fade" id="bookingModal<?= $b['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title">تفاصيل الحجز #<?= $b['id'] ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body">
                                <p><strong>الاسم:</strong> <?= clean($b['name']) ?></p>
                                <p><strong>الهاتف:</strong> <?= clean($b['phone']) ?></p>
                                <p><strong>واتساب:</strong> <?= clean($b['whatsapp'] ?? '-') ?></p>
                                <p><strong>البريد:</strong> <?= clean($b['email'] ?? '-') ?></p>
                                <p><strong>البرنامج:</strong> <?= clean($b['program_title'] ?? '-') ?></p>
                                <p><strong>عدد الأفراد:</strong> <?= $b['persons'] ?></p>
                                <p><strong>ملاحظات العميل:</strong> <?= clean($b['notes'] ?? '-') ?></p>
                                <hr>
                                <form method="POST">
                                    <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">تغيير الحالة</label>
                                        <select name="status" class="form-select">
                                            <?php foreach ($statusLabels as $k => $v): ?>
                                                <option value="<?= $k ?>" <?= $b['status'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">ملاحظات الإدارة</label>
                                        <textarea name="admin_notes" class="form-control" rows="2"><?= clean($b['admin_notes'] ?? '') ?></textarea>
                                    </div>
                                    <button type="submit" name="update_status" value="1" class="btn btn-admin-primary">تحديث</button>
                                </form>
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
