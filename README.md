<<<<<<< HEAD
# 🛒 Multi-Product eCommerce Platform

A **Laravel-based multi-product eCommerce application** designed to manage and sell different types of products with a clean admin panel and scalable category system (Category → Subcategory → Child Category).

---

## 🚀 Features

### 👤 Admin Panel

* Dashboard with statistics
* Category, Subcategory & Child Category management
* Product CRUD with SKU support
* Status & visibility control
* SEO-friendly URLs (slug)

### 🗂 Category System

* Unlimited hierarchical categories
* Category → Subcategory → Child Category relation
* Dynamic dependent dropdowns
* Clean & maintainable structure

### 📦 Product Management

* Multi-category support
* Stock & inventory ready
* SKU-based product identification
* Image upload support

### 🔐 Authentication

* Admin authentication
* Role-ready structure

---

## 🛠 Tech Stack

* **Framework:** Laravel
* **Backend:** PHP
* **Database:** MySQL
* **Frontend:** Blade + Bootstrap
* **Version Control:** Git & GitHub

---

## 📁 Project Structure

```
app/
 ├── Models/
 │   ├── Category.php
 │   ├── SubCategory.php
 │   └── ChildCategory.php
 ├── Http/Controllers/Admin/
 │   ├── CategoryController.php
 │   ├── SubCategoryController.php
 │   └── ChildCategoryController.php

resources/views/admin/
 ├── categories/
 ├── subcategories/
 └── childcategories/
```

---

## ⚙️ Installation

```bash
git clone https://github.com/your-username/multi-product-ecommerce.git
cd multi-product-ecommerce
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

---

## 📸 Screenshots

*Add admin panel & product page screenshots here*

---

## 🤝 Contributing

Contributions are welcome!

1. Fork the repo
2. Create your feature branch
3. Commit your changes
4. Open a Pull Request

---

## 📄 License

This project is open-source and available under the **MIT License**.

---

## ⭐ Support

If you find this project useful, please give it a ⭐ on GitHub.
Happy coding! 🚀
=======
# Multi Ecommerce - Laravel Project

A production-ready multi-vendor ecommerce platform built with Laravel 12, Bootstrap 5, and Blade templates.

## Features

- ✅ Complete Admin Panel with Bootstrap 5
- ✅ Nested Categories (Category → Subcategory → Sub-subcategory)
- ✅ Product Management with Multiple Images
- ✅ Image Resizing (Thumbnail, Medium, Large)
- ✅ Session-based Shopping Cart
- ✅ Order Management System
- ✅ Cash on Delivery Payment
- ✅ Tax & VAT Configuration
- ✅ User Authentication
- ✅ Role-based Access Control (Spatie Permissions)
- ✅ Responsive Frontend Design
- ✅ Product Search & Filtering

## Tech Stack

- **Laravel**: 12.x
- **PHP**: 8.2+
- **MySQL**: 8.0+
- **Bootstrap**: 5.3.3
- **FontAwesome**: 6.5.1
- **Intervention Image**: 3.0
- **Spatie Permissions**: 6.0

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 8.0 or higher
- Laravel Herd (or any PHP server)

### Step 1: Install Dependencies

```bash
cd multi-ecommerce
composer install
npm install
```

### Step 2: Environment Setup

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### Step 3: Database Configuration (MySQL)

**IMPORTANT:** This project uses **MySQL** as the default database.

1. **Create MySQL Database:**
   ```sql
   CREATE DATABASE multi_ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Update your `.env` file with your MySQL credentials:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=multi_ecommerce
   DB_USERNAME=root
   DB_PASSWORD=your_mysql_password
   ```

   **Note:** If using Laravel Herd, default MySQL username is usually `root` with an empty password. See `MYSQL_SETUP.md` for detailed instructions.

### Step 4: Run Migrations & Seeders

```bash
php artisan migrate:fresh --seed
```

This will:
- Create all database tables
- Create an admin user (email: `admin@example.com`, password: `password`)
- Seed sample categories and products

### Step 5: Publish Spatie Permissions

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Step 6: Create Storage Link

```bash
php artisan storage:link
```

### Step 7: Create Upload Directories

Create the following directories in `public/uploads/`:

```bash
mkdir -p public/uploads/products/thumbnails
mkdir -p public/uploads/products/medium
mkdir -p public/uploads/categories/thumbnails
mkdir -p public/uploads/categories/medium
mkdir -p public/uploads/settings
```

### Step 8: Build Assets

```bash
npm run dev
```

For production:

```bash
npm run build
```

### Step 9: Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## Default Credentials

**Admin User:**
- Email: `admin@example.com`
- Password: `password`

**Admin Panel:** `http://localhost:8000/admin/dashboard`

## Project Structure

```
multi-ecommerce/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/          # Admin controllers
│   │       └── Frontend/       # Frontend controllers
│   └── Models/                 # Eloquent models
├── database/
│   ├── migrations/             # Database migrations
│   ├── factories/              # Model factories
│   └── seeders/                # Database seeders
├── resources/
│   └── views/
│       ├── admin/              # Admin panel views
│       ├── frontend/           # Frontend views
│       ├── components/         # Reusable components
│       └── layouts/            # Base layouts
├── routes/
│   ├── web.php                 # Frontend routes
│   └── admin.php               # Admin routes
└── public/
    └── uploads/                # Uploaded files
```

## Admin Panel Features

### Dashboard
- Overview statistics (Products, Categories, Orders)
- Recent orders list

### Categories Management
- Create/Edit/Delete categories
- Nested category support
- Category image upload with resizing
- Status toggle

### Products Management
- Create/Edit/Delete products
- Multiple image upload
- Category & Subcategory assignment
- Stock management
- Price & Discount configuration
- SEO meta fields

### Orders Management
- View all orders
- Order status updates
- Order details view
- Customer information

### Settings
- Site name & logo
- Footer text
- Tax rate configuration
- VAT rate configuration

## Frontend Features

### Home Page
- Hero section
- Featured products
- Category showcase

### Product Listing
- Category filtering
- Search functionality
- Pagination
- Product cards with images

### Product Details
- Image gallery
- Product information
- Add to cart
- Related products

### Shopping Cart
- Add/Remove items
- Update quantities
- Cart total calculation

### Checkout
- Customer information form
- Order summary
- Tax & VAT calculation
- Cash on Delivery

### User Dashboard
- Order history
- Order details
- Profile management

## Image Processing

The application uses Intervention Image to automatically resize uploaded images:

- **Thumbnails**: 300x300px (for listings)
- **Medium**: 600x600px (for product pages)
- **Large**: 1200x1200px (for full-size display)

Images are stored in:
- `public/uploads/products/` (large)
- `public/uploads/products/thumbnails/` (thumbnails)
- `public/uploads/products/medium/` (medium)
- `public/uploads/categories/` (large)
- `public/uploads/categories/thumbnails/` (thumbnails)
- `public/uploads/categories/medium/` (medium)

## Queue Workers (Optional)

For better performance, run queue workers for image processing:

```bash
php artisan queue:work
```

## Production Deployment

### 1. Environment Configuration

Update `.env` for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 2. Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 3. Build Assets

```bash
npm run build
```

### 4. Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 5. HTTPS Configuration

Ensure your web server is configured for HTTPS. Update `.env`:

```env
APP_URL=https://yourdomain.com
```

### 6. Rate Limiting

Rate limiting is configured in `app/Http/Kernel.php` for login routes.

### 7. Queue Workers

Set up a supervisor or systemd service to run queue workers:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/worker.log
```

### 8. Cron Jobs

Add Laravel scheduler to crontab:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Security Features

- CSRF protection on all forms
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade templating)
- Password hashing (bcrypt)
- Role-based access control
- Rate limiting on authentication routes

## Performance Optimizations

- Category caching (via Settings model)
- Image optimization (Intervention Image)
- Database indexing on frequently queried columns
- Pagination for large datasets

## Troubleshooting

### Issue: Images not displaying

**Solution:** Ensure storage link is created:
```bash
php artisan storage:link
```

### Issue: Permission denied errors

**Solution:** Set proper permissions:
```bash
chmod -R 755 storage bootstrap/cache
```

### Issue: Spatie permissions not working

**Solution:** Publish and run migrations:
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Issue: 500 errors after deployment

**Solution:** Clear caches:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues and questions, please create an issue in the repository.

## Author

Built with ❤️ using Laravel
>>>>>>> e9e78d3 (initial commit: multi-product eeommerce project)
