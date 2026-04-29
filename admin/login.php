<?php
/**
 * Admin Login
 */
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'يرجى إدخال اسم المستخدم وكلمة المرور';
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_role'] = $user['role'];

                $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);
                redirect('/admin/dashboard');
            } else {
                $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ في الاتصال بقاعدة البيانات';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة التحكم</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #1010D6, #0808a0);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
        }
        .login-brand {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-brand h3 {
            font-weight: 800;
        }
        .login-brand .brand-land { color: #1010D6; }
        .login-brand .brand-scape { color: #E30613; }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            font-family: 'Cairo', sans-serif;
        }
        .btn-login {
            background: linear-gradient(135deg, #E30613, #c00510);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-weight: 700;
            width: 100%;
            font-size: 1.1rem;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #c00510, #E30613);
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-brand">
            <h3>
                <span class="brand-land">Land</span><span class="brand-scape">Scape</span>
                <small class="d-block text-muted" style="font-size:0.7rem;">لوحة التحكم</small>
            </h3>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= clean($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">اسم المستخدم</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">كلمة المرور</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> تسجيل الدخول
            </button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
