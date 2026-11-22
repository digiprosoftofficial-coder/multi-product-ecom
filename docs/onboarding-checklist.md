# New store onboarding checklist

Use this for every boxed install. First ~5 stores stay manual (no installer).

## Before you start

- [ ] Niche decided (grocery / gadget / fashion / general)
- [ ] Theme slug chosen (or run `php artisan store:preset grocery|gadget|fashion`)
- [ ] Domain ready (or you will register it)
- [ ] Setup fee agreed
- [ ] Support start date and monthly amount agreed
- [ ] Owner name, email, phone collected
- [ ] Payment numbers (bKash / Nagad / Rocket) and delivery charges collected
- [ ] Logo file collected (or placeholder)

## Server

- [ ] New hosting account or new vhost
- [ ] New MySQL database + user
- [ ] Copy this codebase (git clone of the agreed branch, e.g. `prod-*` or `main`)
- [ ] `composer install --no-dev` if vendor is not shipped
- [ ] `npm run build` assets if `public/build` is not shipped
- [ ] Document root = `public/` (Option A) unless that store is legacy
- [ ] `.env` from `.env.production.example` — unique `APP_KEY`, `APP_URL`, DB, mail
- [ ] Do **not** set `FILESYSTEM_PUBLIC_ROOT=base` on Option A hosts
- [ ] `php artisan migrate --force`
- [ ] `php artisan db:seed --class=AdminSeeder` (or equivalent) then change the default password
- [ ] Storage / uploads writable (`storage`, `bootstrap/cache`, `public/uploads`)
- [ ] SSL on

## Super admin setup (you)

- [ ] Log in as super admin
- [ ] Assign existing admin users `super_admin` (after roles exist)
- [ ] Activate the chosen theme, or apply a preset:
      `php artisan store:preset grocery` (organic-v1)
      `php artisan store:preset gadget` (gadget-v1)
      `php artisan store:preset fashion` (organic-v1 + clothing categories)
      Add `--demo-products` only on a fresh demo store
- [ ] Site name, logo, favicon, colors
- [ ] Homepage slides and stats
- [ ] Payment methods + wallet numbers
- [ ] Inside / outside Dhaka delivery charges
- [ ] Contact phone, address, email
- [ ] SEO basics (title, OG image)
- [ ] Categories skeleton or preset
- [ ] Create store owner user (role `store_owner` only)
- [ ] Add the row to the client sheet

## Handover

- [ ] 30 minute training: add product, update stock, change order status
- [ ] Send [owner-guide.md](owner-guide.md)
- [ ] Confirm they cannot see Themes / Site Setting / Settings (after Phase 1)
- [ ] First backup taken
- [ ] Support expiry date in the sheet

## Updates later

```text
git pull   # agreed branch only
# never commit or overwrite this store's .env
php artisan migrate --force   # only if the release has migrations
php artisan config:clear      # if no SSH cache, delete bootstrap/cache/config.php
```

If `config/filesystems.php` differs on a host, do not overwrite it from git without checking. See existing `SHARED_HOSTING.md` and `DEPLOY.md`.
