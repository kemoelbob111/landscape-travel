# Testing Land Scape Travel Website

## Overview
Arabic RTL travel website built with core PHP 8, MySQL, Bootstrap 5 RTL. No frameworks.

## Local Environment Setup

### Prerequisites
- PHP 8.x with extensions: mysql, mbstring, xml, curl, gd
- MySQL server
- Apache with mod_rewrite enabled

### Setup Steps
1. Install dependencies: `sudo apt-get install -y php php-mysql php-mbstring php-xml php-curl php-gd mysql-server apache2 libapache2-mod-php`
2. Start MySQL: `sudo service mysql start`
3. Import database: `sudo mysql < database.sql`
4. Create a local MySQL user (Apache runs as www-data, not root):
   ```
   sudo mysql -e "CREATE USER IF NOT EXISTS 'landscape'@'localhost' IDENTIFIED BY 'landscape_pass'; GRANT ALL PRIVILEGES ON landscape_travel.* TO 'landscape'@'localhost'; FLUSH PRIVILEGES;"
   ```
5. Update `config/database.php` with local credentials (DB_USER='landscape', DB_PASS='landscape_pass')
6. Configure Apache virtual host on port 8080:
   ```
   sudo a2enmod rewrite
   # Create vhost pointing DocumentRoot to repo root with AllowOverride All
   sudo a2ensite landscape && sudo service apache2 restart
   ```
7. Set upload permissions: `sudo chown -R www-data:www-data uploads/`

### Important Notes
- The production `config/database.php` uses root/'' by default — always change for local testing
- After testing, revert database.php credentials back to production defaults before committing
- Apache must have `AllowOverride All` for .htaccess URL rewriting to work
- If Apache fails to start, check `/etc/apache2/ports.conf` for duplicate `Listen` directives

## Key URLs
- Home: `http://localhost:8080/`
- Umrah programs: `http://localhost:8080/umrah`
- Hajj programs: `http://localhost:8080/hajj`
- Program detail: `http://localhost:8080/program/{slug}` (e.g., `umrah-economy-7-days`)
- Booking: `http://localhost:8080/booking`
- Contact: `http://localhost:8080/contact`
- Admin login: `http://localhost:8080/admin/login`
- Admin dashboard: `http://localhost:8080/admin/dashboard`

## Test Credentials
- Admin: username `admin`, password `admin123`

## Key Test Flows

### 1. Booking Flow (Front-end → DB → Admin)
1. Navigate to `/booking`
2. Fill form: name, phone (required), optional WhatsApp/email, select program, set persons
3. Submit → should redirect to `/thank-you`
4. Verify in DB: `SELECT * FROM landscape_travel.bookings ORDER BY id DESC LIMIT 1`
5. Verify in admin: `/admin/bookings` should show the new booking with status "جديد"

### 2. Contact Form Flow
1. Navigate to `/contact`
2. Fill form: name (required), email, subject, message (required)
3. Submit → success message appears on same page
4. Verify in admin: `/admin/messages` should show the message with status "جديد"

### 3. Admin Login
1. Navigate to `/admin/login`
2. Wrong password → Arabic error "اسم المستخدم أو كلمة المرور غير صحيحة"
3. Correct password → redirects to `/admin/dashboard`
4. Dashboard shows stats: bookings, messages, programs, articles counts

### 4. Program CRUD (Admin)
1. `/admin/programs` lists all programs with type badges, prices, edit/delete
2. Click "إضافة برنامج" to add a new program
3. Verify it appears on the front-end Umrah or Hajj page

### 5. Color Control
- Admin → `/admin/colors` lets you change primary blue/red colors
- Colors are stored in `settings` table as `primary_blue` and `primary_red`
- They propagate to front-end via CSS `:root` variables injected in `includes/header.php`

## Architecture Notes
- Router: `index.php` dispatches based on URI, resolves custom slugs via `url_slugs` table
- Admin router: `admin/index.php` handles admin routes, checks session auth
- Settings: key-value pairs in `settings` table, accessed via `getSetting()` helper
- Menus: stored in `menus` table, rendered dynamically in navbar
- All DB queries use PDO prepared statements
- XSS protection via `clean()` function (htmlspecialchars wrapper)

## Devin Secrets Needed
No external secrets required. All testing uses local MySQL credentials.
