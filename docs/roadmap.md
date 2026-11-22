# Roadmap

## Phase 0 — Docs (done when this folder exists)

- [x] Save the plan under `docs/`
- [ ] Read through and adjust prices / support number in `business-model.md` and `owner-guide.md`

## Phase 1 — Roles (done in code)

Goal: store owner can run the shop; they cannot change theme or site design.

- [x] Seeder: `super_admin`, `store_owner`; map legacy `admin` → same access as super admin
- [x] Admin routes: both staff roles can enter; design/settings routes blocked for owner
- [x] Sidebar: hide Site Setting, Settings, Themes from owner
- [x] Login: redirect all three staff roles to admin dashboard
- [ ] Live stores: run `php artisan db:seed --class=AdminSeeder` then create a `store_owner` user

## Phase 2 — Gadget theme (done)

Goal: second sellable look for phones / covers / electronics.

- [x] Complete `gadget-v1` pages listed in `themes.md`
- [x] No checkout logic forks — layout and CSS only

## Phase 3 — Presets and onboarding (done)

Goal: faster second and third store.

- [x] Grocery / gadget / fashion presets via `php artisan store:preset {grocery|gadget|fashion}`
- [x] Use `onboarding-checklist.md` for every new install
- Still no installer for the first 5 stores

## Later (only when needed)

- `fashion-v1` theme
- Backup + update routine across all hosted stores
- Installer wizard
- License / domain lock
- Internal client registry app (replace the spreadsheet)

## Explicitly not now

- Multi-tenant single database
- Owner self-serve signup
- Automatic cPanel deploy API
