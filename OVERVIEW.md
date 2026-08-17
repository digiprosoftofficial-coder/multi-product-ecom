# Multi-Ecommerce — Project Overview

This document describes **what the site does today** and **what is still missing** for the intended product: **one store that sells many product types, organized by Category → Subcategory → Child category**.

> **Goal:** multi-product / multi-category ecommerce (one admin, one catalog, many categories).  
> **Not the goal:** multi-vendor marketplace (many sellers, commissions, vendor panels). That is out of scope.

---

## 1. Current status

| | |
|---|---|
| **What it is** | Single-store Laravel shop that can sell unlimited product types under a 3-level category tree, with session cart, COD checkout, customer accounts, and switchable frontend themes |
| **Category selling** | **Ready in admin and mostly ready on the storefront** — customers can browse by category and subcategory; child-category public pages are not wired yet |
| **What it is not** | A marketplace with multiple vendors |

### Example catalog

```
Electronics → Mobile → Smartphones → iPhone 15
Fashion     → Men    → T-Shirts    → Cotton Tee
Grocery     → Fruits → Citrus      → Orange
```

Admin creates the tree and assigns each product. Customers shop by those levels.

### Tech stack

- **Framework:** Laravel 12 (PHP 8.2+)
- **Database:** MySQL
- **Frontend:** Blade (Bootstrap default + theme packs)
- **Auth / roles:** Spatie `laravel-permission`
- **Images:** Intervention Image
- **Cart:** Session-based (no cart table)

### Default admin

Seeded by `database/seeders/AdminSeeder.php`:

- Email: `admin@example.com`
- Password: `password`

---

## 2. Category system (the core of “multi”)

```
Category → SubCategory → ChildCategory → Product
```

| Layer | Admin | Storefront |
|---|---|---|
| **Category** | Full CRUD + image | `/category/{slug}` lists products and shows that category’s subcategories |
| **Subcategory** | Full CRUD + image; AJAX load when a category is chosen | `/subcategory/{slug}` lists products |
| **Child category** | Full CRUD + image; AJAX load when a subcategory is chosen | Can be assigned on a product, but **no public `/child-category/{slug}` page** |
| **Product** | Must pick a category; subcategory and child category optional | Shop list can filter with `?category=` and `?subcategory=` (not child category) |

Dependent dropdowns in admin product forms: pick Category → Subcategories load → pick Subcategory → Child categories load.

### Where customers actually find categories

- **Default header** (`resources/views/frontend/partials/header.blade.php`): Home + Products only — **no category dropdown**
- **organic-v1 home:** category / subcategory nav and category cards
- **gadget-v1 home + shop:** category cards / filter buttons
- Category page: links to that category’s subcategories
- Shop (`/products`): category links / filters

---

## 3. Current features — storefront

Routes live in `routes/web.php`.

### Pages and catalog

- Home (theme-aware)
- About and Contact (Contact is **static** — no form POST / mailer)
- Product list with search and category/subcategory filters
- Product detail with related products (same category)
- Browse by category and subcategory

### Cart

- Add / update / remove / clear
- Stock checks
- AJAX cart sidebar refresh (`/cart/sidebar`)

### Checkout and orders

- Guest checkout allowed
- Tax and VAT from site settings
- Payment is **Cash on Delivery only**
- Stock decrement in a DB transaction with row lock
- Logged-in customers can view order history and order detail

### Customer account

- Register, login, logout
- Dashboard (recent orders)
- Profile edit (name, email, password)

### Themes

Active theme is setting `active_frontend_theme` (default `organic-v1`). Admin can list, preview, activate, and delete themes under `resources/views/frontend/{slug}/` when both `index.blade.php` and `theme.json` exist.

| Theme | Coverage |
|---|---|
| **organic-v1** (Organic Store) | Home + cart sidebar, including category/subcategory nav on home. Other pages fall back to shared `frontend/*` views |
| **gadget-v1** (Gadget Store) | Home, shop, product, cart, checkout, about, contact + partials |

Public assets also live under `public/organic-v1/` and `public/gadget-v1/`.

---

## 4. Current features — admin (`/admin`)

Routes live in `routes/admin.php`. All admin routes require `auth` + `role:admin`.

- Dashboard stats (products, categories, orders, users)
- **Categories** — full CRUD, images, AJAX subcategories dropdown
- **Subcategories** — full CRUD, images, AJAX child-categories dropdown
- **Child categories** — full CRUD, images
- **Products** — full CRUD, SKU, stock, discount %, compare price, SEO meta, thumbnail + gallery, Intervention resize, 3-level category assignment
- **Orders** — list, search, show, update `order_status` (`pending` → `processing` → `shipped` → `delivered` → `cancelled`)
- **Settings** — site name, logo, footer, tax rate, VAT rate
- **Themes** — list, activate, preview image, delete (if not the active theme)

### Roles and permissions

Spatie is installed. Seeded role is **`admin` only**. Seeded permissions:

- `access-admin`
- `manage-categories`
- `manage-products`
- `manage-orders`
- `manage-settings`

Permissions are **seeded but not enforced** on routes. Middleware only checks `hasRole('admin')`. Registered shoppers have no role; they are plain users.

---

## 5. Data model (as-built)

```
Category → SubCategory → ChildCategory
                ↓
             Product → ProductImage
                ↑
             OrderItem ← Order ← User (nullable for guests)
```

### Eloquent models

`User`, `Category`, `SubCategory`, `ChildCategory`, `Product`, `ProductImage`, `Order`, `OrderItem`, `Setting`

### Domain tables (key columns)

| Table | Key columns |
|---|---|
| `users` | name, email, password |
| `categories` | name, slug, description, status, image |
| `subcategories` | name, slug, category_id, description, status, image |
| `child_categories` | name, slug, sub_category_id, description, status, image |
| `products` | name, slug, sku, category_id, sub_category_id, child_category_id, price, compare_price, discount_price, stock, status, thumbnail, meta_* |
| `product_images` | product_id, filename, is_primary, sort_order |
| `orders` | order_number, user_id (nullable), customer_*, shipping_address, payment_method, payment_status, order_status, subtotal, tax, vat, total, notes |
| `order_items` | order_id, product_id, product_name/sku snapshot, quantity, price, total |
| `settings` | key, value |

---

## 6. Done vs remaining (for this product)

### Category-based selling

| Feature | Status |
|---|---|
| Unlimited categories / subcategories / child categories in admin | Done |
| Assign product to all 3 levels | Done |
| Customer browse by category | Done |
| Customer browse by subcategory | Done |
| Customer browse by child category | **Missing** — no public route or page |
| Shop filter by child category | **Missing** |
| Category → subcategory dropdown in the default / gadget header | **Missing** (organic-v1 home has its own nav) |
| Nested menu showing child categories | **Missing** |

### Store completeness (still useful, not marketplace)

| Feature | Status |
|---|---|
| Online payments (bKash, Nagad, Stripe, etc.) | Missing — checkout hardcodes COD |
| Password reset UI | Missing (table exists only) |
| Email verification | Missing |
| Order confirmation email | Stubbed (commented out) |
| Contact form handler | Missing |
| Wishlist | Missing |
| Product reviews / ratings | Missing |
| Coupon codes | Missing |
| Shipping rate calculator | Missing |
| Invoices / PDF | Missing |
| Admin user management UI | Missing |
| Admin payment-status update | Missing |
| Cancel order + restock | Missing |
| Spatie permissions enforced on routes | Seeded only |

---

## 7. Suggested next-build order

Aligned with category-based selling, not vendors:

1. **Public child-category pages** — `/child-category/{slug}`, list those products, link from subcategory pages.
2. **Category menu in headers** — Category → Subcategory (→ Child category) dropdown on default and gadget-v1 nav, not only on organic-v1 home.
3. **Shop filters** — add child-category filter; keep category/subcategory filters consistent across themes.
4. **Store polish** — password reset, order emails, contact form, cancel + restock.
5. **Payments** — bKash / Nagad / card if COD is not enough.

---

## Related docs

- [SETUP.md](SETUP.md) — install, MySQL, seed admin
- [MYSQL_SETUP.md](MYSQL_SETUP.md) — MySQL troubleshooting
- [BUILD_ASSETS.md](BUILD_ASSETS.md) — Vite / npm build
- [PERFORMANCE_OPTIMIZATION.md](PERFORMANCE_OPTIMIZATION.md) — caching and N+1 notes
