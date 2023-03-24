<?php

use App\Http\Controllers\CesnetCaController;
use App\Http\Controllers\FakeController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ShibbolethController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserStatusController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

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

Route::get('language/{locale}', function ($locale = null) {
    if (isset($locale) && in_array($locale, config('app.locales'))) {
        app()->setLocale($locale);
        session()->put('locale', $locale);
    }

    return redirect()->back();
});

Route::get('/', function () {
    return auth()->user() ? view('dashboard') : view('welcome');
})->name('home');

Route::get('account_created', function () {
    return auth()->user() ? redirect('/') : view('account_created');
});

Route::get('inactive', function () {
    return auth()->user() ? redirect('/') : view('inactive');
});

Route::resource('organizations', OrganizationController::class);
Route::resource('units', UnitController::class);
Route::resource('cesnet-ca', CesnetCaController::class)->only('index', 'create', 'store');

Route::resource('users', UserController::class)->except('show', 'edit', 'update');
Route::controller(UserController::class)->group(function () {
    Route::get('users/{user}', 'show')->withTrashed()->name('users.show');
    Route::patch('users/{user}/role', 'role')->withTrashed()->name('users.role');
    Route::patch('users/{user}/restore', 'restore')->withTrashed()->name('users.restore');
});

Route::patch('users/{user}/status', [UserStatusController::class, 'update'])->withTrashed()->name('users.status');

Route::get('login', [ShibbolethController::class, 'create'])->name('login')->middleware('guest');
Route::get('auth', [ShibbolethController::class, 'store'])->middleware('guest');
Route::get('logout', [ShibbolethController::class, 'destroy'])->name('logout')->middleware('auth');

if (App::environment(['local', 'testing'])) {
    Route::match(['get', 'post'], '/fakelogin/{id?}', [FakeController::class, 'login']);
    Route::get('fakelogout', [FakeController::class, 'logout']);
}
