# MySQL Database Setup Guide

This project is configured to use **MySQL** as the default database.

## Prerequisites

- MySQL 8.0 or higher installed
- MySQL server running
- Access to MySQL root user or a user with database creation privileges

## Step 1: Create MySQL Database

Open your MySQL client (phpMyAdmin, MySQL Workbench, or command line) and create a new database:

### Using MySQL Command Line:

```bash
mysql -u root -p
```

Then run:

```sql
CREATE DATABASE multi_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Using phpMyAdmin:

1. Open phpMyAdmin
2. Click on "New" in the left sidebar
3. Enter database name: `multi_ecommerce`
4. Select collation: `utf8mb4_unicode_ci`
5. Click "Create"

## Step 2: Configure .env File

Update your `.env` file in the project root with your MySQL credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=multi_ecommerce
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

**Important Notes:**
- If using Laravel Herd, the default MySQL credentials might be:
  - Username: `root`
  - Password: (empty or check Herd settings)
- If using XAMPP/WAMP:
  - Username: `root`
  - Password: (usually empty)
- If using a custom MySQL setup, use your credentials

## Step 3: Test Database Connection

Run this command to test if Laravel can connect to your database:

```bash
php artisan db:show
```

If successful, you'll see your database information.

## Step 4: Run Migrations

Once the database connection is working, run migrations:

```bash
php artisan migrate:fresh --seed
```

This will:
- Create all necessary tables
- Seed admin user
- Seed sample categories and products

## Troubleshooting MySQL Connection Issues

### Issue: "Access denied for user"

**Solution:** Check your MySQL username and password in `.env` file.

### Issue: "Unknown database 'multi_ecommerce'"

**Solution:** Make sure you've created the database first (Step 1).

### Issue: "Connection refused"

**Solutions:**
1. Make sure MySQL server is running
2. Check if `DB_HOST` is correct (usually `127.0.0.1` or `localhost`)
3. Verify MySQL port (default is `3306`)

### Issue: "PDOException: could not find driver"

**Solution:** Install PHP MySQL extension:

**Windows (using Herd):**
- Herd usually includes MySQL extension by default
- If not, check PHP extensions in Herd settings

**Linux:**
```bash
sudo apt-get install php-mysql
```

**Mac (using Homebrew):**
```bash
brew install php-mysql
```

### Issue: "SQLSTATE[HY000] [2002] No connection could be made"

**Solutions:**
1. Ensure MySQL service is running
2. Check firewall settings
3. Verify `DB_HOST` in `.env` (try `localhost` instead of `127.0.0.1`)

## Laravel Herd Specific Setup

If you're using Laravel Herd on Windows:

1. Herd includes MySQL by default
2. Default credentials are usually:
   - Host: `127.0.0.1`
   - Port: `3306`
   - Username: `root`
   - Password: (check Herd database settings)

3. You can access MySQL via:
   - Command line: `mysql -u root`
   - Or use Herd's database management tools

## Verify Installation

After running migrations, verify everything is set up correctly:

```bash
php artisan tinker
```

Then in tinker:

```php
DB::table('users')->count();
DB::table('categories')->count();
DB::table('products')->count();
```

You should see counts for users, categories, and products if seeding was successful.

## Production MySQL Setup

For production, consider:

1. **Create a dedicated MySQL user** (not root):
```sql
CREATE USER 'ecommerce_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON multi_ecommerce.* TO 'ecommerce_user'@'localhost';
FLUSH PRIVILEGES;
```

2. **Update `.env` with production credentials**

3. **Enable MySQL query logging** (optional, for debugging)

4. **Set up MySQL backups** (important for production)

## Need Help?

If you encounter any issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify MySQL is running: `mysql -u root -p`
3. Test connection: `php artisan db:show`
4. Check `.env` file syntax (no spaces around `=`)

