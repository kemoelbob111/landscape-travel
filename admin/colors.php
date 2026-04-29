<?php
/**
 * Admin Color Control
 */
$adminPageTitle = 'التحكم بالألوان';
$db = getDB();
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blue = trim($_POST['primary_blue'] ?? '#1010D6');
    $red = trim($_POST['primary_red'] ?? '#E30613');
    
    $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'primary_blue'")->execute([$blue]);
    $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'primary_red'")->execute([$red]);
    
    $success = 'تم تحديث الألوان بنجاح';
}

$settings = getAllSettings();
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-palette me-2"></i> التحكم بالألوان</h4>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="admin-form">
            <h5 class="mb-4 fw-bold">ألوان الموقع الرئيسية</h5>
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label fw-bold">اللون الأزرق الرئيسي</label>
                    <div class="d-flex gap-3 align-items-center">
                        <input type="color" name="primary_blue" value="<?= clean($settings['primary_blue'] ?? '#1010D6') ?>" 
                               style="width:80px; height:50px; border:none; cursor:pointer;" id="blueColor">
                        <input type="text" class="form-control" value="<?= clean($settings['primary_blue'] ?? '#1010D6') ?>" 
                               dir="ltr" id="blueText" onchange="document.getElementById('blueColor').value=this.value" style="max-width:150px;">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">اللون الأحمر الرئيسي</label>
                    <div class="d-flex gap-3 align-items-center">
                        <input type="color" name="primary_red" value="<?= clean($settings['primary_red'] ?? '#E30613') ?>" 
                               style="width:80px; height:50px; border:none; cursor:pointer;" id="redColor">
                        <input type="text" class="form-control" value="<?= clean($settings['primary_red'] ?? '#E30613') ?>" 
                               dir="ltr" id="redText" onchange="document.getElementById('redColor').value=this.value" style="max-width:150px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-admin-primary"><i class="fas fa-save me-2"></i> حفظ الألوان</button>
            </form>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="admin-form">
            <h5 class="mb-4 fw-bold">معاينة</h5>
            <div id="preview" style="padding:20px; border-radius:12px; text-align:center;">
                <div style="background: <?= clean($settings['primary_blue'] ?? '#1010D6') ?>; color:white; padding:20px; border-radius:8px; margin-bottom:15px;">
                    <h5>اللون الأزرق الرئيسي</h5>
                    <p style="margin:0; opacity:0.8;">يستخدم في الهيدر والعناوين</p>
                </div>
                <div style="background: <?= clean($settings['primary_red'] ?? '#E30613') ?>; color:white; padding:20px; border-radius:8px; margin-bottom:15px;">
                    <h5>اللون الأحمر الرئيسي</h5>
                    <p style="margin:0; opacity:0.8;">يستخدم في الأزرار والعناصر المميزة</p>
                </div>
                <button class="btn" style="background: linear-gradient(135deg, <?= clean($settings['primary_red'] ?? '#E30613') ?>, #c00510); color:white; border-radius:50px; padding:10px 30px;">
                    زر تجريبي
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('blueColor').addEventListener('input', function() {
    document.getElementById('blueText').value = this.value;
});
document.getElementById('redColor').addEventListener('input', function() {
    document.getElementById('redText').value = this.value;
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
