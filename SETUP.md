# Setup Instructions

## Quick Start Guide

Follow these steps to get your Multi Ecommerce project up and running:

### 1. Install Composer Dependencies

```bash
cd multi-ecommerce
composer install
```

This will install:
- Laravel Framework 12
- Spatie Laravel Permission
- Intervention Image

### 2. Install NPM Dependencies

```bash
npm install
```

This will install:
- Bootstrap 5.3.3
- Popper.js
- FontAwesome 6.5.1
- Vite & Laravel Vite Plugin

### 3. Environment Configuration

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Database Setup (MySQL)

**This project uses MySQL as the default database.**

1. **Create the MySQL database:**
   
   Open MySQL client and run:
   ```sql
   CREATE DATABASE multi_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Update your `.env` file with MySQL credentials:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=multi_ecommerce
   DB_USERNAME=root
   DB_PASSWORD=your_mysql_password
   ```

   **For Laravel Herd users:**
   - Username: `root`
   - Password: (usually empty, check Herd database settings)

   **For XAMPP/WAMP users:**
   - Username: `root`
   - Password: (usually empty)

   See `MYSQL_SETUP.md` for detailed MySQL setup instructions and troubleshooting.

### 5. Publish Spatie Permissions

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 6. Run Migrations & Seeders

```bash
php artisan migrate:fresh --seed
```

This will:
- Create all database tables
- Create admin user (email: `admin@example.com`, password: `password`)
- Seed sample categories and products

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Create Upload Directories

Create the following directories manually or run:

**Windows (PowerShell):**
```powershell
New-Item -ItemType Directory -Force -Path "public\uploads\products\thumbnails"
New-Item -ItemType Directory -Force -Path "public\uploads\products\medium"
New-Item -ItemType Directory -Force -Path "public\uploads\categories\thumbnails"
New-Item -ItemType Directory -Force -Path "public\uploads\categories\medium"
New-Item -ItemType Directory -Force -Path "public\uploads\settings"
```

**Linux/Mac:**
```bash
mkdir -p public/uploads/products/thumbnails
mkdir -p public/uploads/products/medium
mkdir -p public/uploads/categories/thumbnails
mkdir -p public/uploads/categories/medium
mkdir -p public/uploads/settings
```

### 9. Build Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 10. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Login Credentials

**Admin Panel:**
- URL: `http://localhost:8000/admin/dashboard`
- Email: `admin@example.com`
- Password: `password`

**Frontend:**
- URL: `http://localhost:8000`
- You can register a new account or use the admin credentials

## Troubleshooting

### Images not displaying
- Ensure `php artisan storage:link` is run
- Check directory permissions on `public/uploads`

### Permission errors
- Run `chmod -R 755 storage bootstrap/cache` (Linux/Mac)
- Ensure web server has write permissions

### Spatie permissions not working
- Make sure you ran: `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
- Run migrations: `php artisan migrate`

### 500 errors
- Clear caches: `php artisan config:clear && php artisan cache:clear && php artisan view:clear`

## Next Steps

1. Configure your mail settings in `.env` for order confirmations
2. Set up queue workers for image processing (optional)
3. Configure your production environment
4. Customize the design and branding

## Production Deployment

See the main README.md for production deployment instructions.

