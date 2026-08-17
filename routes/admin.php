<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\ChildCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ThemeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    Route::get('categories/{category}/subcategories', [CategoryController::class, 'getSubcategories'])->name('categories.subcategories');
    
    // Subcategories
    Route::resource('subcategories', SubCategoryController::class);
    Route::get('subcategories/{subcategory}/childcategories', [SubCategoryController::class, 'getChildCategories'])->name('subcategories.childcategories');
    
    // Child Categories
    Route::resource('childcategories', ChildCategoryController::class);
    
    // Products
    Route::resource('products', ProductController::class);
    Route::delete('products/images/{productImage}', [ProductController::class, 'deleteImage'])->name('products.images.destroy');
    
    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    
    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Themes
    Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
    Route::get('themes/preview/{slug}', [ThemeController::class, 'preview'])->name('themes.preview');
    Route::post('themes/activate', [ThemeController::class, 'activate'])->name('themes.activate');
    Route::delete('themes', [ThemeController::class, 'destroy'])->name('themes.destroy');
});

