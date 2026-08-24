# Production deploy checklist

Use this before going live. Templates: `.env.production.example` → copy to server `.env`.

## 1. Server requirements

- [ ] PHP 8.2+ with extensions: `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `gd` (or `imagick`)
- [ ] Composer 2
- [ ] Node.js 18+ (build assets on server or CI, then deploy `public/build`)
- [ ] MySQL 8
- [ ] HTTPS (Let’s Encrypt or host SSL)
- [ ] Web root points to `public/` (not project root)

## 2. Environment

- [ ] Copy `.env.production.example` to `.env`
- [ ] `php artisan key:generate` (or paste a strong `APP_KEY`)
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false` (mandatory)
- [ ] `APP_URL=https://yourdomain.com` (exact public URL)
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] `SESSION_ENCRYPT=true` (recommended)
- [ ] `LOG_LEVEL=error`
- [ ] MySQL credentials set and database created (`utf8mb4`)

## 3. Install & migrate

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
```

- [ ] Do **not** run `--seed` on production unless you intentionally need demo data
- [ ] If you must seed once: change admin password immediately, or create a new admin and remove `admin@example.com`

## 4. Optimize

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

- [ ] `storage/` and `bootstrap/cache/` writable by the web user
- [ ] Queue worker running if `QUEUE_CONNECTION=database` (Supervisor/systemd)

Example Supervisor program:

```ini
[program:multi-ecommerce-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/multi-ecommerce/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/multi-ecommerce/storage/logs/worker.log
```

Scheduler (cron):

```bash
* * * * * cd /var/www/multi-ecommerce && php artisan schedule:run >> /dev/null 2>&1
```

## 5. Mail

- [ ] Real SMTP configured (`MAIL_MAILER=smtp`, host, user, app password)
- [ ] `MAIL_FROM_ADDRESS` matches allowed sender
- [ ] Test contact form and order emails

## 6. Store content (admin)

- [ ] Site name, logo, favicon
- [ ] Contact phone / email / address
- [ ] Currency symbol + code (e.g. `৳` / `BDT`)
- [ ] Payment wallet numbers (bKash / Nagad / Rocket)
- [ ] SEO default meta description + optional OG image
- [ ] About, Privacy, Terms, Delivery, Returns pages
- [ ] Products and categories published

## 7. Security smoke test

- [ ] Admin URL requires login; non-admin users cannot open `/admin/*`
- [ ] Default seed password not in use
- [ ] HTTPS redirects work; mixed content free
- [ ] `.env` not web-accessible
- [ ] `storage/` and `vendor/` not publicly listed

## 8. Functional smoke test

- [ ] Home, shop, category, product page load
- [ ] Add to cart → checkout → place order (COD + one wallet method)
- [ ] Order visible in admin; status update works
- [ ] Invoice / thank-you page OK
- [ ] Register / login / profile
- [ ] Contact form submits
- [ ] Images load via `storage` link
- [ ] `/sitemap.xml` and `/robots.txt` OK
- [ ] `/up` health endpoint returns 200

## 9. After go-live

- [ ] Submit sitemap in Google Search Console
- [ ] Database backup schedule (daily recommended)
- [ ] Monitor `storage/logs/laravel.log`
- [ ] Document how to deploy updates (`git pull` → migrate → cache → build)

## Rollback (quick)

```bash
php artisan down
# restore previous release / DB backup
php artisan config:clear
php artisan up
```

## Common issues

| Problem | Fix |
|---------|-----|
| 500 after deploy | `APP_DEBUG=false` + check `storage/logs`; clear caches |
| Images 404 | `php artisan storage:link` |
| CSRF / session lost | HTTPS + `SESSION_SECURE_COOKIE=true` + correct `APP_URL` |
| Mail not sending | SMTP credentials; avoid `MAIL_MAILER=log` in production |
| Vite assets missing on admin | Run `npm run build` and deploy `public/build` |
