<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Routes for AuthController (User Management)
    Route::get('/users', [AuthController::class, 'list'])->name('users.list');
    Route::get('/users/create', [AuthController::class, 'create'])->name('users.create');
    Route::post('/users', [AuthController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AuthController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AuthController::class, 'renew'])->name('users.renew');
    Route::delete('/users/{user}', [AuthController::class, 'remove'])->name('users.remove');

    // Routes for ItemController
    Route::get('/items', [ItemController::class, 'list'])->name('item.list');
    Route::post('/items', [ItemController::class, 'add'])->name('item.add');
    Route::get('/items/{id}', [ItemController::class, 'detail'])->name('item.detail');
    Route::put('/items/{id}', [ItemController::class, 'renew'])->name('item.renew');
    Route::delete('/items/{id}', [ItemController::class, 'remove'])->name('item.remove');

    // Routes for StockController
    Route::get('/stock', [StockController::class, 'list'])->name('stock.list');
    Route::post('/stock/in', [StockController::class, 'addIn'])->name('stock.addIn');
    Route::post('/stock/out', [StockController::class, 'addOut'])->name('stock.addOut');
    Route::get('/stock/download', [StockController::class, 'download'])->name('stock.download');
});
