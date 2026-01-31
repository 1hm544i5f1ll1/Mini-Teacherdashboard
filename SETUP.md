# How to Run This Project

This is a PHP-based School Management System. Follow these steps to set it up and run it.

## Prerequisites

1. **PHP 7.4 or higher** (with extensions: pdo_mysql, mbstring, session)
2. **MySQL/MariaDB** (port 3306 or 3307)
3. **Web Server** (Apache/Nginx) OR PHP built-in server

## Option 1: Using XAMPP/WAMP (Recommended for Windows)

### Step 1: Install XAMPP
- Download and install [XAMPP](https://www.apachefriends.org/)
- Make sure Apache and MySQL are running

### Step 2: Configure Database
1. Open `app/config/config.php` and verify database settings:
   ```php
   DB_HOST: '127.0.0.1'
   DB_PORT: 3307  // or 3306 (check your MySQL port in XAMPP)
   DB_USER: 'root'
   DB_PASS: ''    // usually empty for XAMPP
   DB_NAME: 'sohag_kg_system'
   ```

2. **Important**: Check your MySQL port:
   - Open XAMPP Control Panel
   - Click "Config" next to MySQL
   - Check `my.ini` for the port (usually 3306 or 3307)
   - Update `DB_PORT` in `config.php` if needed

### Step 3: Install Database
Run the installation script to create the database and tables:

```bash
php scripts/install.php
```

Or manually:
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create database: `sohag_kg_system`
3. Import `database/schema.sql`
4. Import `database/seed.sql`
5. **Registration MVP**: Run the migration once to enable registration flow (draft → submitted → approved/rejected → locked):
   ```bash
   mysql -u root -p sohag_kg_system < database/migrations/001_registration_mvp.sql
   ```
   Or in phpMyAdmin: open `database/migrations/001_registration_mvp.sql`, copy contents, and run in the SQL tab.

### Step 4: Configure Web Server

**Option A: Using Apache (XAMPP)**
1. Copy the project folder to `C:\xampp\htdocs\`
2. Rename it to `sohag-kg-system` (or update `.htaccess` RewriteBase)
3. Update `public/.htaccess` RewriteBase if needed:
   ```
   RewriteBase /sohag-kg-system/public/
   ```
4. Access: `http://localhost/sohag-kg-system/public/`

**Option B: Using PHP Built-in Server (Quick Start)**
```bash
cd public
php -S localhost:8000
```
Then access: `http://localhost:8000`

**Note**: For PHP built-in server, you may need to update `APP_URL` in `config.php`:
```php
define('APP_URL', 'http://localhost:8000');
```

## Option 2: Using PHP Built-in Server (No Apache needed)

### Step 1: Install PHP and MySQL
- Install PHP from [php.net](https://www.php.net/downloads.php)
- Install MySQL from [mysql.com](https://dev.mysql.com/downloads/)

### Step 2: Configure Database
1. Start MySQL service
2. Update `app/config/config.php` with your MySQL credentials
3. Run: `php scripts/install.php`

### Step 3: Start PHP Server
```bash
cd public
php -S localhost:8000
```

### Step 4: Access Application
Open browser: `http://localhost:8000`

## Default Login Credentials

After running `seed.sql`, you can login with:

**Manager:**
- Username: `manager`
- Password: `change_me`

**Teacher:**
- Username: `ahmed`
- Password: `teacher123`

## Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify port number (3306 or 3307)
- Check username/password in `config.php`
- Ensure database `sohag_kg_system` exists

### 404 Errors / Routes Not Working
- If using Apache: Enable `mod_rewrite` module
- Check `.htaccess` file exists in `public/` folder
- Verify `RewriteBase` in `.htaccess` matches your URL path
- If using PHP built-in server: Routes should work automatically

### Permission Errors
- Ensure `storage/` folder is writable
- Check `storage/logs/` has write permissions

### Port Already in Use
- Change port in PHP server: `php -S localhost:8080`
- Or stop the service using that port

## Project Structure

```
Mini-Teacherdashboard/
├── app/              # Application code (MVC structure)
├── public/           # Web root (entry point: index.php)
├── database/         # SQL schema and seed files
├── storage/          # Logs and uploads
├── scripts/          # Installation and utility scripts
└── config.php        # Main configuration
```

## Quick Start Commands

```bash
# 1. Install database
php scripts/install.php

# 2. Start PHP server
cd public
php -S localhost:8000

# 3. Open browser
# http://localhost:8000
```

## Next Steps

1. Login with default credentials
2. Change default passwords
3. Configure school settings
4. Add students and classes
5. Start using the system!
