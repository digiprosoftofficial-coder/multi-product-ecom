# Themes

## Rule

Super admin chooses and edits the theme. Store owner never sees the Themes screen and cannot activate or delete a theme.

## How themes work in this app

- Slug folder: `resources/views/frontend/{slug}/`
- Required files: `index.blade.php` and `theme.json`
- Public assets: `public/{slug}/` (CSS/JS/images)
- Active theme setting: `active_frontend_theme` (default `organic-v1`)
- Controllers resolve views like `frontend.{theme}.cart` and fall back to a shared view if the theme file is missing

Admin theme tools: `app/Http/Controllers/Admin/ThemeController.php` and routes under `admin/themes`.

## Catalog (keep it small)

| Slug | Niche | Status |
|------|--------|--------|
| `organic-v1` | Grocery / daily goods | Primary, use as the reference |
| `gadget-v1` | Phones, covers, electronics | Sellable: shop, product, cart, checkout, thank-you, account, info pages |
| `fashion-v1` | Clothing / shoes | Not built yet |
| `general-v1` | Mixed new businesses | Optional later |

Do not create a new git repo per niche. Same app, different theme folder + homepage preset.

## Completeness checklist (every theme)

A theme is sellable only when these have theme-specific views (or a documented, styled fallback):

- Home
- Shop / category
- Product
- Cart
- Checkout
- Thank-you
- About
- Contact
- Header, footer, mobile nav, cart sidebar
- 404 (or shared layout that still looks on-brand)

Checkout and payment logic stay shared. Theme only changes layout and CSS.

## Two edit layers

**Safe edit (per store, in admin — super admin only)**

- Logo, favicon
- Header / footer colors
- Homepage slides, stats, section titles
- Page banners and info-page copy
- Payment wallet numbers, delivery zones

**Code edit (you, locally, then deploy)**

- New theme
- New layout, CSS, components
- Never edit a live store’s theme files by hand on cPanel if you can avoid it

## Phase 2 status

`gadget-v1` now has theme views for shop/category, product, cart, checkout, thank-you, about, contact, dashboard, orders, profile, delivery/returns/privacy/terms, and a themed 404. Fashion remains a **preset** on `organic-v1` until `fashion-v1` is built.
