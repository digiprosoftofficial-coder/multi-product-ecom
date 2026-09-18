# Roles and permissions

## Current code

- All admin routes use `middleware(['auth', 'role:admin'])` in `routes/admin.php`.
- `LoginController` redirects users with role `admin` to `admin.dashboard`.
- `AdminSeeder` creates role `admin` and user `admin@example.com`.
- Permissions (`access-admin`, `manage-categories`, `manage-products`, `manage-orders`, `manage-settings`) are seeded but not checked on routes.

## Target roles

| Role | Who | Purpose |
|------|-----|---------|
| `super_admin` | You | Full store: design, settings, themes, plus everything the owner can do |
| `store_owner` | Client | Day-to-day shop only |
| `admin` | Legacy | Treat as `super_admin` so existing live logins keep working |
| Customer (no admin role) | Shoppers | Storefront + their own orders only |

## Menu and route split

**Both roles**

- Dashboard
- Categories (keep for now; hide later if owners misuse it)
- Products
- Orders (including status + invoice)
- Customers
- Messages
- Reports

**Super admin only**

- Site Setting: homepage, about, shop page, product page, contact page, cart, checkout, info pages
- Settings: brand, contact, payment, tax/VAT, shipping, SEO, header/footer colors
- Themes: list, preview, activate, delete

Store owner must get **403** if they open those URLs directly. Hiding the sidebar is not enough.

## Implementation notes

1. Create `super_admin` and `store_owner` in the seeder.
2. Give both `access-admin` so `/admin` opens.
3. Give `super_admin` (and legacy `admin`) `manage-design` + `manage-settings`.
4. Change admin route middleware from `role:admin` to something both staff roles pass (for example `role:super_admin|store_owner|admin` or a permission `access-admin`).
5. Protect design/settings route groups with `role:super_admin|admin` or `permission:manage-design`.
6. Sidebar (`resources/views/admin/partials/sidebar.blade.php`): wrap Site Setting, Settings, and Themes in `@role('super_admin')` (and allow legacy `admin`).
7. `LoginController`: send `super_admin`, `store_owner`, and `admin` to the admin dashboard.
8. After deploy on a live store, assign existing admin users `super_admin` before creating the owner user.

## First owner user

On each store after deploy, run once:

```bash
php artisan db:seed --class=AdminSeeder
```

That creates the new roles and gives every existing `admin` user `super_admin` as well. Legacy `admin` still works without seeding.

Then create the owner (tinker or a user form later):

```php
$user = \App\Models\User::create([
    'name' => 'Owner Name',
    'email' => 'owner@example.com',
    'password' => 'choose-a-strong-password',
]);
$user->assignRole('store_owner');
```

- Role: `store_owner` only
- Do not give them SSH, cPanel, or `.env`
