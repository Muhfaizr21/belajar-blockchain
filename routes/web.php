<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [ExpenseController::class, 'index']);
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
