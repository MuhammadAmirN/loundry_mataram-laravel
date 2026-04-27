<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/order', [User\OrderController::class, 'index'])->name('user.orders');
    Route::patch('/order', [User\OrderController::class, 'store'])->name('user.orders.store');
    Route::delete('/myorders', [User\OrderController::class, 'updateStatus'])->name('order.update-status');
    Route::get('/my-orders/{order}/cancel', [User\OrderController::class, 'cancel'])->name('user.order.cancel');
});

require __DIR__.'/auth.php';

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function(){
    Route::get('/dashboard', fn() => redirect()->route('admin.admin.index'))->name('dashboard');
    
    Route::resource('sercives', Admin\ServicesController::class);

// Orders
    Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/payment', [Admin\OrderController::class, 'updatePayment'])->name('orders.update-payment');

    // Reports
    Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');
});


Route::get('dashboard', function (){
    if (auth()->user()->isAdmim()){
        if (auth()->user()->isAdmin()){
            return redirect()->route('user.my-orders');
        }
    }
});