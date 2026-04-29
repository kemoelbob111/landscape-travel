<?php
/**
 * Admin Settings
 */
$adminPageTitle = 'إعدادات الموقع';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDB();
    $fields = ['site_name','site_tagline','phone','whatsapp','email','address',
               'facebook','twitter','instagram','youtube','tiktok',
               'meta_title','meta_description','meta_keywords',
               'google_map_embed','footer_text'];
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([trim($_POST[$field]), $field]);
        }
    }

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif','svg','webp'];
        if (in_array(strtolower($ext), $allowed)) {
            $filename = 'logo_' . time() . '.' . $ext;
            $path = 'uploads/' . $filename;
            move_uploaded_file($_FILES['logo']['tmp_name'], __DIR__ . '/../' . $path);
            $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'logo'")->execute([$path]);
        }
    }

    $success = 'تم حفظ الإعدادات بنجاح';
}

$settings = getAllSettings();
include __DIR__ . '/includes/header.php';
?>

<div class="admin-page-header">
    <h4><i class="fas fa-cog me-2"></i> إعدادات الموقع</h4>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= $success ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <!-- General -->
        <div class="col-lg-6">
            <div class="admin-form">
                <h5 class="mb-4 fw-bold">معلومات عامة</h5>
                <div class="mb-3">
                    <label class="form-label">اسم الموقع</label>
                    <input type="text" name="site_name" class="form-control" value="<?= clean($settings['site_name'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">شعار الموقع</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label class="form-label">الشعار (نص)</label>
                    <input type="text" name="site_tagline" class="form-control" value="<?= clean($settings['site_tagline'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">نص التذييل</label>
                    <input type="text" name="footer_text" class="form-control" value="<?= clean($settings['footer_text'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="col-lg-6">
            <div class="admin-form">
                <h5 class="mb-4 fw-bold">معلومات التواصل</h5>
                <div class="mb-3">
                    <label class="form-label">رقم الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="<?= clean($settings['phone'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">واتساب</label>
                    <input type="text" name="whatsapp" class="form-control" value="<?= clean($settings['whatsapp'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="<?= clean($settings['email'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">العنوان</label>
                    <input type="text" name="address" class="form-control" value="<?= clean($settings['address'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Social -->
        <div class="col-lg-6">
            <div class="admin-form">
                <h5 class="mb-4 fw-bold">وسائل التواصل</h5>
                <div class="mb-3">
                    <label class="form-label">فيسبوك</label>
                    <input type="url" name="facebook" class="form-control" value="<?= clean($settings['facebook'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">تويتر</label>
                    <input type="url" name="twitter" class="form-control" value="<?= clean($settings['twitter'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">إنستغرام</label>
                    <input type="url" name="instagram" class="form-control" value="<?= clean($settings['instagram'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">يوتيوب</label>
                    <input type="url" name="youtube" class="form-control" value="<?= clean($settings['youtube'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">تيك توك</label>
                    <input type="url" name="tiktok" class="form-control" value="<?= clean($settings['tiktok'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- SEO -->
        <div class="col-lg-6">
            <div class="admin-form">
                <h5 class="mb-4 fw-bold">SEO</h5>
                <div class="mb-3">
                    <label class="form-label">عنوان الميتا</label>
                    <input type="text" name="meta_title" class="form-control" value="<?= clean($settings['meta_title'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">وصف الميتا</label>
                    <textarea name="meta_description" class="form-control" rows="3"><?= clean($settings['meta_description'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">كلمات مفتاحية</label>
                    <input type="text" name="meta_keywords" class="form-control" value="<?= clean($settings['meta_keywords'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">خريطة جوجل (كود التضمين)</label>
                    <textarea name="google_map_embed" class="form-control" rows="3"><?= clean($settings['google_map_embed'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-admin-primary btn-lg px-5">
            <i class="fas fa-save me-2"></i> حفظ الإعدادات
        </button>
    </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
