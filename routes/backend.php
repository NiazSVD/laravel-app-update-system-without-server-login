<?php

use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\FoodController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\OrderItemController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\SiteSettingController;
use App\Http\Controllers\Backend\TeamController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashobard
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')->middleware('permission:dashboard view');

    // Basic Settings
    Route::get('settings/site', [SiteSettingController::class, 'index'])->name('settings.site')
        ->middleware('permission:setting update');
    Route::post('settings/site/update', [SiteSettingController::class, 'update'])
        ->name('settings.site.update')->middleware('permission:setting update');

    // User Management
    Route::post('/users', [UserController::class, 'store'])->name('users.store')
        ->middleware('permission:user create');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')
        ->middleware('permission:user edit');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')
        ->middleware('permission:user delete');

    // User Management - Admin
    Route::get('/users', [UserController::class, 'index'])->name('users.index')
        ->middleware('permission:user view');

    // User Management - Employee
    Route::get('/users/employee', [UserController::class, 'employee'])->name('users.employee.index')
        ->middleware('permission:user view');

    // User Management - Vendor
    Route::get('/users/vendor', [UserController::class, 'vendor'])->name('users.vendor.index')
        ->middleware('permission:user view');

    // User Management - Deleveryman
    Route::get('/users/delivery', [UserController::class, 'delivery'])->name('users.delivery.index')
        ->middleware('permission:user view');

    // User Management - Other
    Route::get('/users/other', [UserController::class, 'other'])->name('users.other.index')
        ->middleware('permission:user view');

    // Role Management
    Route::get('/roles', [RoleController::class, 'index'])->name('roles')
        ->middleware('permission:role view');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store')
        ->middleware('permission:role create');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update')
        ->middleware('permission:role edit');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')
        ->middleware('permission:role delete');

    // Role Permissions
    Route::get('roles/{role}/permissions', [RoleController::class, 'editPermissions'])
        ->name('roles.permissions.edit')->middleware('permission:role permission');

    Route::post('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])
        ->name('roles.permissions.update')->middleware('permission:role permission');

    // Food
    Route::prefix('food')->name('food.')->group(function () {
        Route::get('/index', [FoodController::class, 'index'])->name('index');
        Route::get('/vendorindex', [FoodController::class, 'vendorindex'])->name('vendorindex');
        Route::get('/vendorindexfood', [FoodController::class, 'vendorindexfood'])->name('vendorindexfood');
        Route::get('/create', [FoodController::class, 'create'])->name('create');
        Route::post('/store', [FoodController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [FoodController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [FoodController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [FoodController::class, 'destroy'])->name('delete');
    });
    // team
    Route::prefix('team')->name('team.')->group(function () {
        Route::get('/index', [TeamController::class, 'index'])->name('index');
        Route::get('/create', [TeamController::class, 'create'])->name('create');
        Route::post('/store', [TeamController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [TeamController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [TeamController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [TeamController::class, 'destroy'])->name('delete');
    });
    // order
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/index', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/store', [OrderController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [OrderController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [OrderController::class, 'destroy'])->name('delete');
    });
    // order-item
    Route::prefix('order-item')->name('order-item.')->group(function () {
        Route::get('/index', [OrderItemController::class, 'index'])->name('index');
        Route::get('/create', [OrderItemController::class, 'create'])->name('create');
        Route::post('/store', [OrderItemController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [OrderItemController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [OrderItemController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [OrderItemController::class, 'destroy'])->name('delete');
    });
    // category
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/index', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
    });

    // profile

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit/{id}', [ProfileController::class, 'index'])->name('edit');
        Route::post('/update/{id}', [ProfileController::class, 'update'])->name('update');
        Route::post('/updatePassword', [ProfileController::class, 'updatePassword'])->name('updatePassword');
    });


    Route::prefix('user')->name('user.')->group(function () {
        Route::get('order_list', [OrderController::class, 'order_list'])->name('order_list');
        Route::delete('order_cancel/{id}', [OrderController::class, 'order_cancel'])->name('order_cancel');
        // Route::get('order_cancelled', [OrderController::class, 'order_cancelled'])->name('order_cancelled');
        // Route::get('order_delivered', [OrderController::class, 'order_delivered'])->name('order_delivered');
    });
});
