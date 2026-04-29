# Land Scape Travel - موقع لاند سكيب للسفر والسياحة

موقع عربي متكامل لشركة سفر وسياحة متخصصة في رحلات الحج والعمرة.

## المتطلبات

- PHP 8.0 أو أعلى
- MySQL 5.7 أو أعلى
- Apache مع mod_rewrite مفعّل
- إضافات PHP: PDO, pdo_mysql, mbstring, gd, curl

## خطوات التثبيت على Hostinger

### 1. رفع الملفات

1. قم بتحميل جميع ملفات المشروع
2. ارفع الملفات إلى مجلد `public_html` على الاستضافة عبر File Manager أو FTP
3. تأكد من رفع ملف `.htaccess`

### 2. إنشاء قاعدة البيانات

1. ادخل إلى لوحة تحكم Hostinger (hPanel)
2. اذهب إلى **Databases** → **MySQL Databases**
3. أنشئ قاعدة بيانات جديدة باسم تختاره
4. أنشئ مستخدم لقاعدة البيانات وامنحه جميع الصلاحيات
5. اذهب إلى **phpMyAdmin** واستورد ملف `database.sql`

### 3. تعديل إعدادات الاتصال

افتح ملف `config/database.php` وعدّل البيانات التالية:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'اسم_قاعدة_البيانات');
define('DB_USER', 'اسم_المستخدم');
define('DB_PASS', 'كلمة_المرور');
```

### 4. صلاحيات المجلدات

تأكد من أن مجلد `uploads` لديه صلاحيات الكتابة:
```
chmod -R 755 uploads/
```

### 5. الدخول للوحة التحكم

- الرابط: `https://yourdomain.com/admin`
- اسم المستخدم: `admin`
- كلمة المرور: `admin123`

> **مهم:** قم بتغيير كلمة المرور فوراً بعد أول تسجيل دخول

## هيكل الملفات

```
/
├── config/             # إعدادات الاتصال والتكوين
│   ├── config.php
│   └── database.php
├── includes/           # ملفات الهيدر والفوتر
│   ├── header.php
│   └── footer.php
├── pages/              # صفحات الموقع
│   ├── home.php
│   ├── umrah.php
│   ├── hajj.php
│   ├── program-details.php
│   ├── about.php
│   ├── blog.php
│   ├── contact.php
│   ├── booking.php
│   └── thank-you.php
├── admin/              # لوحة التحكم
│   ├── index.php       # Router
│   ├── login.php
│   ├── dashboard.php
│   ├── programs.php
│   ├── bookings.php
│   ├── settings.php
│   └── ...
├── assets/             # ملفات CSS, JS, صور
│   ├── css/
│   ├── js/
│   └── images/
├── uploads/            # ملفات مرفوعة
├── database.sql        # ملف قاعدة البيانات
├── .htaccess           # إعادة كتابة الروابط
├── index.php           # الموجه الرئيسي
└── README.md           # هذا الملف
```

## لوحة التحكم

تتضمن لوحة التحكم:

- **لوحة المعلومات**: إحصائيات الحجوزات والرسائل
- **البرامج**: إضافة/تعديل/حذف برامج الحج والعمرة
- **التصنيفات**: إدارة تصنيفات البرامج
- **الحجوزات**: عرض وإدارة طلبات الحجز مع تصدير CSV
- **الرسائل**: إدارة رسائل التواصل
- **المدونة**: نظام مقالات كامل
- **الشهادات**: آراء العملاء
- **البانرات**: إدارة صور الشريحة الرئيسية
- **الصفحات**: إنشاء صفحات مخصصة
- **القوائم**: التحكم بروابط القائمة الرئيسية
- **الروابط**: تغيير URLs الصفحات
- **الألوان**: تغيير ألوان الموقع الرئيسية
- **الإعدادات**: معلومات الموقع، SEO، وسائل التواصل
- **المستخدمين**: إدارة حسابات المشرفين

## التقنيات المستخدمة

- PHP 8 (Core PHP)
- MySQL (PDO)
- Bootstrap 5 RTL
- Font Awesome 6
- Swiper.js
- AOS (Animate On Scroll)
- Google Fonts (Cairo)

## الأمان

- PDO Prepared Statements لمنع SQL Injection
- htmlspecialchars لمنع XSS
- كلمات مرور مشفرة بـ bcrypt
- حماية ملفات حساسة عبر .htaccess
- Security Headers

## الدعم

للمساعدة أو الاستفسارات، تواصل مع فريق التطوير.
