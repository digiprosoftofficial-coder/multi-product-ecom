# Digiprosoft Multi Ecommerce

Laravel 12 multi-product ecommerce store with admin panel, nested categories, cart/checkout, and SEO-ready product pages.

## Features

- Admin panel (dashboard, categories, products, orders, customers, reports, settings)
- Nested categories with configurable depth
- Product CRUD with gallery images, stock, discount, SEO meta
- Session cart, checkout (COD, bKash, Nagad, Rocket)
- Tax/VAT, currency settings, contact form
- Role-based admin access (Spatie Permissions)
- Sitemap, robots.txt, Open Graph / JSON-LD SEO

## Tech stack

- Laravel 12, PHP 8.2+
- MySQL 8
- Blade + Bootstrap 5
- Intervention Image, Spatie Permissions

## Local setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure MySQL in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=multi_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

Then:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
```

Serve with Laravel Herd, or:

```bash
php artisan serve
```

### Default admin (local seed only)

- Email: `admin@example.com`
- Password: `password`

**Never use these credentials in production.** Change them immediately after seeding, or create a new admin and delete the default one.

Admin: `/admin/dashboard`

## Production

See **[DEPLOY.md](DEPLOY.md)** for the full checklist.

Quick start:

```bash
cp .env.production.example .env
# Edit .env: APP_KEY, DB_*, MAIL_*, APP_URL
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link
npm ci && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## License

MIT
