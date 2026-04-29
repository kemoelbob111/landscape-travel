<?php
/**
 * Admin Dashboard
 */
$adminPageTitle = 'لوحة التحكم';

$db = getDB();
$bookingsCount = $db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$newBookings = $db->query("SELECT COUNT(*) FROM bookings WHERE status = 'new'")->fetchColumn();
$messagesCount = $db->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$unreadMessages = $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read = 0")->fetchColumn();
$programsCount = $db->query("SELECT COUNT(*) FROM programs WHERE is_active = 1")->fetchColumn();
$articlesCount = $db->query("SELECT COUNT(*) FROM articles WHERE is_published = 1")->fetchColumn();

$recentBookings = $db->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentMessages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-tachometer-alt me-2"></i> لوحة التحكم</h4>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="dash-card blue position-relative">
            <i class="fas fa-calendar-check card-icon"></i>
            <span class="card-number"><?= $bookingsCount ?></span>
            <span class="card-label">إجمالي الحجوزات</span>
            <?php if ($newBookings > 0): ?>
                <span class="badge bg-danger mt-2"><?= $newBookings ?> جديد</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="dash-card red position-relative">
            <i class="fas fa-envelope card-icon"></i>
            <span class="card-number"><?= $messagesCount ?></span>
            <span class="card-label">الرسائل</span>
            <?php if ($unreadMessages > 0): ?>
                <span class="badge bg-danger mt-2"><?= $unreadMessages ?> غير مقروء</span>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="dash-card green position-relative">
            <i class="fas fa-suitcase card-icon"></i>
            <span class="card-number"><?= $programsCount ?></span>
            <span class="card-label">البرامج النشطة</span>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="dash-card orange position-relative">
            <i class="fas fa-newspaper card-icon"></i>
            <span class="card-number"><?= $articlesCount ?></span>
            <span class="card-label">المقالات المنشورة</span>
        </div>
    </div>
</div>

<!-- Recent Tables -->
<div class="row g-4">
    <!-- Recent Bookings -->
    <div class="col-lg-6">
        <div class="admin-table">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">أحدث الحجوزات</h6>
                <a href="/admin/bookings" class="btn btn-sm btn-admin-primary">عرض الكل</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البرنامج</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentBookings as $b): ?>
                        <tr>
                            <td><?= clean($b['name']) ?></td>
                            <td><?= clean(mb_substr($b['program_title'] ?? '-', 0, 25)) ?></td>
                            <td>
                                <span class="badge-status badge-<?= $b['status'] ?>">
                                    <?php
                                    $statuses = ['new' => 'جديد', 'contacted' => 'تم التواصل', 'confirmed' => 'مؤكد', 'cancelled' => 'ملغي'];
                                    echo $statuses[$b['status']] ?? $b['status'];
                                    ?>
                                </span>
                            </td>
                            <td><?= date('m/d', strtotime($b['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="col-lg-6">
        <div class="admin-table">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">أحدث الرسائل</h6>
                <a href="/admin/messages" class="btn btn-sm btn-admin-primary">عرض الكل</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الموضوع</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentMessages as $m): ?>
                        <tr>
                            <td><?= clean($m['name']) ?></td>
                            <td><?= clean(mb_substr($m['subject'] ?? '-', 0, 25)) ?></td>
                            <td>
                                <?php if ($m['is_read']): ?>
                                    <span class="badge bg-secondary">مقروء</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">جديد</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('m/d', strtotime($m['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
