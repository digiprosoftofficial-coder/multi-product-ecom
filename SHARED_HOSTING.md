# Shared hosting (cPanel) — Option A

Recommended setup for `shohojkenakata` / any shared host **without SSH**.

## Goal

- Full Laravel app stays in `public_html/`
- Domain **Document Root** = `public_html/public`
- After that: `git pull` on this branch is enough for code updates

## One-time cPanel setup

1. **Domains** → your domain → **Document Root** → set to:
   ```
   public_html/public
   ```
   Save / wait a minute for it to apply.

2. Confirm `.env` in `public_html/` (project root, not inside `public/`) has at least:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   QUEUE_CONNECTION=sync
   ```
   Do **not** set `FILESYSTEM_PUBLIC_ROOT=base` for Option A.

3. **Uploads (images)** must live here:
   ```
   public_html/public/uploads/
   ```
   If you previously put images in `public_html/uploads/`, move that folder into `public/uploads/` (File Manager → Move).

4. Permissions (File Manager):
   - `storage/` → 775 (and subfolders)
   - `bootstrap/cache/` → 775
   - `public/uploads/` → 775

5. One-time on server (already done if you uploaded them):
   - `vendor/` (from `composer install --no-dev`)
   - `public/build/` (from `npm run build`)

   These are **not** in git. Keep them on the server; `git pull` will not remove them.

6. Database: create MySQL DB + user in cPanel, put credentials in `.env`, then run migrations once (cPanel Terminal if available, or ask host / temporary SSH):
   ```bash
   php artisan migrate --force
   php artisan config:clear
   ```
   Without Terminal: delete `bootstrap/cache/config.php` if it exists after `.env` changes.

## Everyday updates (no SSH)

```text
cPanel Git → Pull from prod-shohojkenakata
```

Only re-upload `vendor/` or `public/build/` when `composer.json` / frontend assets change and you rebuilt locally.

## Checklist after Document Root change

- [ ] Homepage loads (not a directory listing / 403)
- [ ] CSS works (`public/build` present)
- [ ] Product images load from `/uploads/...`
- [ ] Admin login works
- [ ] `.env` not open in browser (`https://domain/.env` should fail)

## If Document Root cannot be changed

Root `.htaccess` rewrites traffic into `public/` as a fallback. Still prefer changing Document Root when the host allows it.
