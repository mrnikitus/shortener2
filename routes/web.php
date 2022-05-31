<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AddressController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (request()->user()) {
        return redirect()->route('addresses.index');
    } else {
       return redirect('https://mrnikitus.ru/'); // Здесь указать адрес, куда перенаправляться с ошибочных адресов и главной страницы
    }
})->name('root');
Route::middleware('auth')->group(function () {
    Route::get('addresses/all', [AddressController::class, 'addresses_all'])->name('addresses.all');
    Route::resource('addresses', AddressController::class)->except(['edit', 'destroy']);
    Route::delete('addresses/{address}', [AddressController::class, 'destroy'])->withTrashed()->name('addresses.destroy');
    Route::get('addresses/{address}/switch', [AddressController::class, 'in_use'])->name('addresses.in_use');
    Route::get('addresses/{address}/statistic', [AddressController::class, 'statistic'])->name('addresses.statistic');
    Route::resource('users', UsersController::class)->except(['destroy']);
    Route::delete('users/{user}', [UsersController::class, 'destroy'])->withTrashed()->name('users.destroy');
});
Route::get('{slug}', [AddressController::class, 'redirect'])->name('addresses.slug');

