<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ThemeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/print', [\App\Http\Controllers\Admin\ReportController::class, 'print'])->name('reports.print');
    
    // Categories
    Route::get('categories/children', [CategoryController::class, 'children'])->name('categories.children');
    Route::resource('categories', CategoryController::class);
    
    // Products
    Route::resource('products', ProductController::class);
    Route::delete('products/images/{productImage}', [ProductController::class, 'deleteImage'])->name('products.images.destroy');
    
    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    
    Route::get('homepage', [\App\Http\Controllers\Admin\HomepageController::class, 'index'])->name('homepage.index');
    Route::put('homepage', [\App\Http\Controllers\Admin\HomepageController::class, 'update'])->name('homepage.update');

    Route::get('about', [\App\Http\Controllers\Admin\PageController::class, 'about'])->name('about.index');
    Route::put('about', [\App\Http\Controllers\Admin\PageController::class, 'updateAbout'])->name('about.update');

    Route::get('shop-page', [\App\Http\Controllers\Admin\PageController::class, 'shop'])->name('shop-page.index');
    Route::put('shop-page', [\App\Http\Controllers\Admin\PageController::class, 'updateShop'])->name('shop-page.update');

    Route::get('contact-page', [\App\Http\Controllers\Admin\PageController::class, 'contact'])->name('contact-page.index');
    Route::put('contact-page', [\App\Http\Controllers\Admin\PageController::class, 'updateContact'])->name('contact-page.update');

    Route::get('pages', [\App\Http\Controllers\Admin\PageController::class, 'index'])->name('pages.index');
    Route::put('pages', [\App\Http\Controllers\Admin\PageController::class, 'update'])->name('pages.update');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Themes
    Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
    Route::get('themes/preview/{slug}', [ThemeController::class, 'preview'])->name('themes.preview');
    Route::post('themes/activate', [ThemeController::class, 'activate'])->name('themes.activate');
    Route::delete('themes', [ThemeController::class, 'destroy'])->name('themes.destroy');
});

