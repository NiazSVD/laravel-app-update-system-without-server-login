<?php

use App\Http\Controllers\Backend\CartController;
use App\Http\Controllers\Backend\FoodController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\Update\SystemUpdateController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // /test for food template
    Route::get('/foods', [FoodController::class, 'foodListView'])->name('food.list');
    Route::get('/food', [FoodController::class, 'foodListViewAdmin'])->name('food.listAdmin');
    Route::get('/vendor/foods', [FoodController::class, 'foodListViewVendor'])->name('food.listVendor');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQty']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeItem']);

    // /place order
    Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::post('/order/status/{id}', [OrderController::class, 'status'])->name('order.status');

    Route::get('/order/pending/{id}', [OrderController::class, 'pending'])->name('order.pending');
    Route::get('/order/delivered/{id}', [OrderController::class, 'delivered'])->name('order.delivered');
    Route::get('/order/cancel/{id}', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::get('/order/paid/{id}', [OrderController::class, 'paid'])->name('order.paid');
    Route::get('/order/unpaid/{id}', [OrderController::class, 'unpaid'])->name('order.unpaid');
    Route::get('/order/cod/{id}', [OrderController::class, 'cod'])->name('order.cod');
    Route::get('/order/due/{id}', [OrderController::class, 'due'])->name('order.due');

    // /employee order list
    Route::get('/employee/order/list', [OrderController::class, 'employeeOrderList'])->name('employee.order.list');
    Route::get('/employee/delivey/list', [OrderController::class, 'employeedeliverylist'])->name('employee.delivery.list');
    Route::get('/employee/cancel/list', [OrderController::class, 'employeecancellist'])->name('employee.cancel.list');

    // vendor order
    Route::get('/vendor/order/list', [OrderController::class, 'vendorOrderList'])->name('vendor.order.list');
    Route::get('/vendor/order/delivey/list', [OrderController::class, 'vendorOrderDliveyList'])->name('vendor.order.delivey.list');
    Route::get('/vendor/order/cancel/list', [OrderController::class, 'vendorOrderCancelList'])->name('vendor.order.cancel.list');

    // /ajax route for vendor to select today's food menu
    Route::get('/vendor/category-wise-food/{categoryId}', [FoodController::class, 'getFoodsByCategory']);
    Route::post('/vendor/today-food-store', [FoodController::class, 'todayFoodstore'])->name('vendor.today.food.store');
});

// /test for food template
Route::get('/foods', [FoodController::class, 'foodListView'])->name('food.list');
Route::get('/food', [FoodController::class, 'foodListViewAdmin'])->name('food.listAdmin');
Route::get('/vendor/foods', [FoodController::class, 'foodListViewVendor'])->name('food.listVendor');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'updateQty']);
Route::delete('/cart/remove/{id}', [CartController::class, 'removeItem']);

// /place order
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::post('/order/status/{id}', [OrderController::class, 'status'])->name('order.status');

Route::get('/order/pending/{id}', [OrderController::class, 'pending'])->name('order.pending');
Route::get('/order/delivered/{id}', [OrderController::class, 'delivered'])->name('order.delivered');
Route::get('/order/cancel/{id}', [OrderController::class, 'cancel'])->name('order.cancel');
Route::get('/order/paid/{id}', [OrderController::class, 'paid'])->name('order.paid');
Route::get('/order/unpaid/{id}', [OrderController::class, 'unpaid'])->name('order.unpaid');
Route::get('/order/cod/{id}', [OrderController::class, 'cod'])->name('order.cod');
Route::get('/order/due/{id}', [OrderController::class, 'due'])->name('order.due');

// /employee order list
Route::get('/employee/order/list', [OrderController::class, 'employeeOrderList'])->name('employee.order.list');
Route::get('/employee/delivey/list', [OrderController::class, 'employeedeliverylist'])->name('employee.delivery.list');
Route::get('/employee/cancel/list', [OrderController::class, 'employeecancellist'])->name('employee.cancel.list');

// vendor order
Route::get('/vendor/order/list', [OrderController::class, 'vendorOrderList'])->name('vendor.order.list');
Route::get('/vendor/order/delivey/list', [OrderController::class, 'vendorOrderDliveyList'])->name('vendor.order.delivey.list');
Route::get('/vendor/order/cancel/list', [OrderController::class, 'vendorOrderCancelList'])->name('vendor.order.cancel.list');

// /ajax route for vendor to select today's food menu
Route::get('/vendor/category-wise-food/{categoryId}', [FoodController::class, 'getFoodsByCategory']);
Route::post('/vendor/today-food-store', [FoodController::class, 'todayFoodstore'])->name('vendor.today.food.store');









Route::middleware('auth')->group(function () {
    Route::get('update', [SystemUpdateController::class, 'index'])->name('admin.update.index');
    Route::post('update/upload', [SystemUpdateController::class, 'upload'])->name('admin.update.upload');
    Route::post('update/backup', [SystemUpdateController::class, 'backup'])->name('admin.update.backup');
    Route::get('update/backups/{file}', [SystemUpdateController::class, 'downloadBackup'])->name('admin.update.backup.download');
    Route::post('update/run', [SystemUpdateController::class, 'run'])->name('admin.update.run');
    ///delete old backup
    Route::delete('update/backups/{file}', [SystemUpdateController::class, 'deleteBackup'])->name('admin.update.backup.delete');

});










require __DIR__.'/auth.php';
require __DIR__.'/backend.php';
