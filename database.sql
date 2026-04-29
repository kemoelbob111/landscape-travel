-- Land Scape Travel Database
-- MySQL Database Schema

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET collation_connection = 'utf8mb4_unicode_ci';

CREATE DATABASE IF NOT EXISTS `landscape_travel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `landscape_travel`;

-- =====================
-- SETTINGS TABLE
-- =====================
CREATE TABLE `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'Land Scape Travel'),
('site_tagline', 'رحلتك للحج والعمرة تبدأ من هنا'),
('logo', 'assets/images/logo.png'),
('phone', '+966 50 000 0000'),
('whatsapp', '+966500000000'),
('email', 'info@landscapetravel.com'),
('address', 'الرياض، المملكة العربية السعودية'),
('facebook', 'https://facebook.com/landscapetravel'),
('twitter', 'https://twitter.com/landscapetravel'),
('instagram', 'https://instagram.com/landscapetravel'),
('youtube', 'https://youtube.com/landscapetravel'),
('tiktok', ''),
('meta_title', 'Land Scape Travel - رحلات الحج والعمرة'),
('meta_description', 'شركة لاند سكيب للسفر والسياحة - أفضل عروض الحج والعمرة بأسعار منافسة وخدمات متميزة'),
('meta_keywords', 'حج, عمرة, سياحة, سفر, رحلات, مكة, المدينة'),
('primary_blue', '#1010D6'),
('primary_red', '#E30613'),
('google_map_embed', ''),
('footer_text', 'جميع الحقوق محفوظة © 2024 Land Scape Travel');

-- =====================
-- USERS TABLE (Admin)
-- =====================
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100),
    `role` ENUM('super_admin', 'admin', 'editor') DEFAULT 'admin',
    `is_active` TINYINT(1) DEFAULT 1,
    `last_login` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin: admin / admin123
INSERT INTO `users` (`username`, `password`, `name`, `email`, `role`) VALUES
('admin', '$2y$10$7uZzxpJIk1yCcORUBKwaye3eNHpjcaiXpQHYT5ToQzx3JoJfFR6Vm', 'مدير النظام', 'admin@landscapetravel.com', 'super_admin');

-- =====================
-- CATEGORIES TABLE
-- =====================
CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL UNIQUE,
    `type` ENUM('hajj', 'umrah', 'blog') DEFAULT 'umrah',
    `description` TEXT,
    `sort_order` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`name`, `slug`, `type`, `sort_order`) VALUES
('عمرة اقتصادية', 'umrah-economy', 'umrah', 1),
('عمرة VIP', 'umrah-vip', 'umrah', 2),
('عمرة رمضان', 'umrah-ramadan', 'umrah', 3),
('عمرة مع زيارة', 'umrah-visit', 'umrah', 4),
('حج VIP', 'hajj-vip', 'hajj', 1),
('حج اقتصادي', 'hajj-economy', 'hajj', 2),
('حج مباشر', 'hajj-direct', 'hajj', 3),
('حج منى / كدانة', 'hajj-mina-kadana', 'hajj', 4),
('نصائح الحج', 'hajj-tips', 'blog', 1),
('نصائح العمرة', 'umrah-tips', 'blog', 2),
('أخبار وفعاليات', 'news', 'blog', 3);

-- =====================
-- PROGRAMS TABLE
-- =====================
CREATE TABLE `programs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(300) NOT NULL,
    `slug` VARCHAR(300) NOT NULL UNIQUE,
    `category_id` INT,
    `type` ENUM('hajj', 'umrah') DEFAULT 'umrah',
    `duration` VARCHAR(100),
    `price` DECIMAL(10,2) DEFAULT 0.00,
    `price_note` VARCHAR(200),
    `image` VARCHAR(500),
    `gallery` TEXT,
    `short_description` TEXT,
    `description` LONGTEXT,
    `itinerary` LONGTEXT,
    `hotels` LONGTEXT,
    `features` LONGTEXT,
    `includes` LONGTEXT,
    `excludes` LONGTEXT,
    `terms` LONGTEXT,
    `is_featured` TINYINT(1) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `views` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Programs
INSERT INTO `programs` (`title`, `slug`, `category_id`, `type`, `duration`, `price`, `image`, `short_description`, `description`, `itinerary`, `hotels`, `features`, `includes`, `excludes`, `terms`, `is_featured`, `sort_order`) VALUES
('عمرة اقتصادية 7 أيام', 'umrah-economy-7-days', 1, 'umrah', '7 أيام / 6 ليالي', 15000.00, 'assets/images/program1.jpg', 'برنامج عمرة اقتصادي مميز لمدة 7 أيام يشمل الإقامة والتنقلات', '<p>برنامج عمرة اقتصادي مميز يوفر لكم تجربة روحانية كاملة بأسعار مناسبة. يشمل البرنامج الإقامة في فنادق قريبة من الحرم مع توفير جميع وسائل الراحة.</p>', '<ul><li>اليوم الأول: الوصول والتسكين</li><li>اليوم الثاني: أداء العمرة</li><li>اليوم الثالث - السادس: زيارات دينية</li><li>اليوم السابع: المغادرة</li></ul>', '<p>فندق 4 نجوم قريب من الحرم المكي - فندق 4 نجوم في المدينة المنورة</p>', '<ul><li>قرب من الحرم</li><li>وجبات يومية</li><li>مرشد ديني</li><li>تنقلات مريحة</li></ul>', '<ul><li>تذاكر طيران ذهاب وعودة</li><li>إقامة فندقية</li><li>وجبات (إفطار وعشاء)</li><li>تنقلات بين المشاعر</li><li>تأشيرة عمرة</li><li>مرشد ديني</li></ul>', '<ul><li>المصاريف الشخصية</li><li>وجبة الغداء</li><li>أي خدمات غير مذكورة</li></ul>', '<ul><li>الأسعار قابلة للتغيير</li><li>يرجى الحجز قبل 30 يوم</li><li>سياسة الإلغاء: قبل 15 يوم</li></ul>', 1, 1),

('عمرة VIP 10 أيام', 'umrah-vip-10-days', 2, 'umrah', '10 أيام / 9 ليالي', 35000.00, 'assets/images/program2.jpg', 'برنامج عمرة VIP فاخر مع إقامة في أرقى الفنادق', '<p>برنامج عمرة VIP فاخر يوفر لكم أعلى مستويات الراحة والخدمة. إقامة في فنادق 5 نجوم مطلة على الحرم مباشرة مع خدمات خاصة.</p>', '<ul><li>اليوم الأول: استقبال VIP والتسكين</li><li>اليوم الثاني: أداء العمرة مع مرشد خاص</li><li>اليوم الثالث - التاسع: برنامج زيارات شامل</li><li>اليوم العاشر: المغادرة</li></ul>', '<p>فندق 5 نجوم مطل على الحرم المكي - فندق 5 نجوم في المدينة المنورة</p>', '<ul><li>إطلالة على الحرم</li><li>جميع الوجبات</li><li>مرشد خاص</li><li>سيارة خاصة</li><li>خدمة الغرف</li></ul>', '<ul><li>تذاكر طيران درجة رجال الأعمال</li><li>إقامة فندقية 5 نجوم</li><li>جميع الوجبات</li><li>سيارة خاصة</li><li>مرشد ديني خاص</li><li>تأشيرة عمرة</li></ul>', '<ul><li>المصاريف الشخصية</li><li>التسوق</li></ul>', '<ul><li>الحجز قبل 45 يوم</li><li>دفعة مقدمة 50%</li></ul>', 1, 2),

('حج VIP مميز', 'hajj-vip-premium', 5, 'hajj', '15 يوم / 14 ليلة', 75000.00, 'assets/images/program3.jpg', 'برنامج حج VIP مميز مع أفضل الخدمات والإقامة الفاخرة', '<p>برنامج حج VIP يوفر لكم تجربة حج لا تُنسى مع أعلى مستويات الخدمة والراحة. إقامة في خيام مكيفة فاخرة في منى وعرفات.</p>', '<ul><li>اليوم الأول - الثالث: الوصول والإقامة في مكة</li><li>اليوم الرابع: يوم التروية</li><li>اليوم الخامس: الوقوف بعرفة</li><li>اليوم السادس: المزدلفة ورمي الجمرات</li><li>اليوم السابع - العاشر: أيام التشريق</li><li>اليوم الحادي عشر - الخامس عشر: المدينة المنورة</li></ul>', '<p>فندق 5 نجوم مطل على الحرم - خيام VIP مكيفة في منى وعرفات - فندق 5 نجوم بالمدينة</p>', '<ul><li>خيام VIP مكيفة</li><li>جميع الوجبات</li><li>مرشد ديني</li><li>رعاية طبية</li><li>تنقلات فاخرة</li></ul>', '<ul><li>تذاكر طيران</li><li>إقامة فندقية</li><li>خيام VIP</li><li>جميع الوجبات</li><li>تنقلات</li><li>تأشيرة حج</li></ul>', '<ul><li>المصاريف الشخصية</li><li>الهدي والأضاحي</li></ul>', '<ul><li>الحجز قبل 3 أشهر</li><li>دفعة مقدمة 30%</li></ul>', 1, 3),

('حج اقتصادي', 'hajj-economy', 6, 'hajj', '12 يوم / 11 ليلة', 35000.00, 'assets/images/program4.jpg', 'برنامج حج اقتصادي بأسعار مناسبة مع خدمات متكاملة', '<p>برنامج حج اقتصادي يوفر لكم فرصة أداء فريضة الحج بأسعار مناسبة مع ضمان جودة الخدمات المقدمة.</p>', '<ul><li>اليوم الأول - الثاني: الوصول والإقامة</li><li>يوم التروية وعرفة</li><li>أيام التشريق</li><li>زيارة المدينة المنورة</li></ul>', '<p>فندق 3 نجوم في مكة - فندق 3 نجوم في المدينة</p>', '<ul><li>أسعار مناسبة</li><li>خدمات متكاملة</li><li>مرشد ديني</li></ul>', '<ul><li>تذاكر طيران</li><li>إقامة فندقية</li><li>وجبات أساسية</li><li>تنقلات</li><li>تأشيرة</li></ul>', '<ul><li>المصاريف الشخصية</li><li>الهدي</li><li>بعض الوجبات</li></ul>', '<ul><li>الحجز قبل شهرين</li><li>دفعة مقدمة 40%</li></ul>', 1, 4);

-- =====================
-- BOOKINGS TABLE
-- =====================
CREATE TABLE `bookings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `program_id` INT,
    `name` VARCHAR(200) NOT NULL,
    `phone` VARCHAR(50) NOT NULL,
    `whatsapp` VARCHAR(50),
    `email` VARCHAR(100),
    `program_title` VARCHAR(300),
    `persons` INT DEFAULT 1,
    `notes` TEXT,
    `status` ENUM('new', 'contacted', 'confirmed', 'cancelled') DEFAULT 'new',
    `admin_notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`program_id`) REFERENCES `programs`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================
-- CONTACT MESSAGES TABLE
-- =====================
CREATE TABLE `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `email` VARCHAR(100),
    `phone` VARCHAR(50),
    `subject` VARCHAR(300),
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================
-- BANNERS TABLE
-- =====================
CREATE TABLE `banners` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(300),
    `subtitle` VARCHAR(500),
    `image` VARCHAR(500) NOT NULL,
    `link` VARCHAR(500),
    `button_text` VARCHAR(100),
    `sort_order` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `banners` (`title`, `subtitle`, `image`, `button_text`, `link`, `sort_order`) VALUES
('رحلتك للحج والعمرة تبدأ من هنا', 'اكتشف أفضل البرامج والعروض الحصرية مع لاند سكيب للسفر والسياحة', 'assets/images/banner1.jpg', 'احجز الآن', '#programs', 1),
('عروض العمرة المميزة', 'برامج عمرة متنوعة تناسب جميع الميزانيات', 'assets/images/banner2.jpg', 'شاهد البرامج', '/umrah', 2);

-- =====================
-- BLOG / ARTICLES TABLE
-- =====================
CREATE TABLE `articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(500) NOT NULL,
    `slug` VARCHAR(500) NOT NULL UNIQUE,
    `category_id` INT,
    `image` VARCHAR(500),
    `excerpt` TEXT,
    `content` LONGTEXT,
    `author` VARCHAR(100),
    `meta_title` VARCHAR(300),
    `meta_description` TEXT,
    `is_published` TINYINT(1) DEFAULT 0,
    `views` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `articles` (`title`, `slug`, `category_id`, `image`, `excerpt`, `content`, `author`, `is_published`) VALUES
('نصائح مهمة قبل السفر للعمرة', 'umrah-travel-tips', 10, 'assets/images/blog1.jpg', 'تعرف على أهم النصائح التي يجب معرفتها قبل السفر لأداء العمرة', '<h3>نصائح مهمة قبل السفر للعمرة</h3><p>يجب على المعتمر التحضير جيداً قبل السفر لأداء العمرة. إليك أهم النصائح:</p><ul><li>تأكد من صلاحية جواز السفر</li><li>احجز مبكراً للحصول على أفضل الأسعار</li><li>تعرف على مناسك العمرة قبل السفر</li><li>احمل الأدوية اللازمة</li><li>ارتدِ ملابس مريحة ومناسبة</li></ul>', 'فريق لاند سكيب', 1),
('أركان الحج وواجباته', 'hajj-pillars', 9, 'assets/images/blog2.jpg', 'تعرف على أركان الحج وواجباته وسننه في هذا المقال الشامل', '<h3>أركان الحج</h3><p>للحج أركان أربعة لا يصح بدونها:</p><ol><li>الإحرام</li><li>الوقوف بعرفة</li><li>طواف الإفاضة</li><li>السعي بين الصفا والمروة</li></ol><h3>واجبات الحج</h3><ul><li>الإحرام من الميقات</li><li>الوقوف بعرفة إلى الغروب</li><li>المبيت بمزدلفة</li><li>رمي الجمرات</li><li>الحلق أو التقصير</li><li>طواف الوداع</li></ul>', 'فريق لاند سكيب', 1);

-- =====================
-- TESTIMONIALS TABLE
-- =====================
CREATE TABLE `testimonials` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `title` VARCHAR(200),
    `content` TEXT NOT NULL,
    `image` VARCHAR(500),
    `rating` INT DEFAULT 5,
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `testimonials` (`name`, `title`, `content`, `rating`, `sort_order`) VALUES
('أحمد محمد', 'معتمر', 'تجربة رائعة مع لاند سكيب ترافل. كانت الرحلة منظمة بشكل ممتاز والفنادق قريبة جداً من الحرم. أنصح الجميع بالتعامل معهم.', 5, 1),
('فاطمة علي', 'حاجة', 'الحمد لله أديت فريضة الحج مع لاند سكيب وكانت التجربة لا تُنسى. الخدمة ممتازة والمرشد الديني كان متميزاً.', 5, 2),
('محمد خالد', 'معتمر VIP', 'خدمة VIP حقيقية من البداية للنهاية. فندق مطل على الحرم مباشرة وسيارة خاصة. شكراً لاند سكيب.', 5, 3);

-- =====================
-- MENUS TABLE
-- =====================
CREATE TABLE `menus` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `url` VARCHAR(500) NOT NULL,
    `parent_id` INT DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `target` VARCHAR(20) DEFAULT '_self',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menus` (`title`, `url`, `sort_order`) VALUES
('الرئيسية', '/', 1),
('برامج العمرة', '/umrah', 2),
('برامج الحج', '/hajj', 3),
('من نحن', '/about', 4),
('المدونة', '/blog', 5),
('اتصل بنا', '/contact', 6);

-- =====================
-- PAGES TABLE
-- =====================
CREATE TABLE `pages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(300) NOT NULL,
    `slug` VARCHAR(300) NOT NULL UNIQUE,
    `content` LONGTEXT,
    `meta_title` VARCHAR(300),
    `meta_description` TEXT,
    `is_published` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pages` (`title`, `slug`, `content`, `is_published`) VALUES
('من نحن', 'about', '<h2>من نحن</h2><p>شركة لاند سكيب للسفر والسياحة هي إحدى الشركات الرائدة في مجال تنظيم رحلات الحج والعمرة. نسعى دائماً لتقديم أفضل الخدمات لعملائنا الكرام بأسعار تنافسية وجودة عالية.</p><h3>رؤيتنا</h3><p>أن نكون الخيار الأول والأفضل في مجال رحلات الحج والعمرة على مستوى المنطقة.</p><h3>رسالتنا</h3><p>تقديم تجربة روحانية متكاملة للحجاج والمعتمرين مع أعلى معايير الجودة والراحة.</p><h3>لماذا نحن؟</h3><ul><li>خبرة تزيد عن 15 عاماً</li><li>فريق متخصص ومحترف</li><li>فنادق قريبة من الحرم</li><li>أسعار تنافسية</li><li>خدمة عملاء 24/7</li><li>برامج متنوعة تناسب الجميع</li></ul>', 1);

-- =====================
-- URL SLUGS TABLE (for custom URL mapping)
-- =====================
CREATE TABLE `url_slugs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `original_slug` VARCHAR(300) NOT NULL UNIQUE,
    `custom_slug` VARCHAR(300) NOT NULL UNIQUE,
    `entity_type` VARCHAR(50) NOT NULL,
    `entity_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `url_slugs` (`original_slug`, `custom_slug`, `entity_type`) VALUES
('umrah', 'umrah', 'page'),
('hajj', 'hajj', 'page'),
('about', 'about', 'page'),
('blog', 'blog', 'page'),
('contact', 'contact', 'page'),
('booking', 'booking', 'page');
