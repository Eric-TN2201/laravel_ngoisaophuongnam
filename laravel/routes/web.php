<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\StoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController;

use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PageController;
use App\Http\Controllers\Client\NewsController as ClientNewsController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\ServiceController as ClientServiceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/admin/dashboard', fn() => redirect()->route('admin.home'));

// static pages
Route::get('/gioi-thieu', [PageController::class, 'about'])->name('about');
Route::get('/lien-he', [ContactController::class, 'index'])->name('contact');
Route::post('/lien-he', [ContactController::class, 'send'])->name('contact.send');
Route::post('/tu-van-mien-phi', [ContactController::class, 'sendConsultation'])->name('consultation.send');

// products and categories
Route::prefix('san-pham')->name('product.')->group(function () {
    Route::get('/', [ClientProductController::class, 'index'])->name('index');
    Route::get('/chi-tiet/{product}', [ClientProductController::class, 'show'])->name('show');
    Route::get('/{category}/{subCategory?}', [ClientProductController::class, 'category'])->name('category');
});

// news
Route::prefix('tin-tuc')->name('news.')->group(function () {
    Route::get('/', [ClientNewsController::class, 'index'])->name('index');
    Route::get('/{news}', [ClientNewsController::class, 'show'])->name('show');
});

// services
Route::prefix('dich-vu')->name('service.')->group(function () {
    Route::get('/', [ClientServiceController::class, 'index'])->name('index');
    Route::get('/{service}', [ClientServiceController::class, 'show'])->name('show');
});

Route::prefix('/spn/admin')->name('admin.')->group(function () {
    Auth::routes();

    Route::middleware('admin.auth')->group(function () {
        // Our Story admin page
        Route::get('loi-gioi-thieu', [StoryController::class, 'edit'])->name('story.edit');
        Route::post('loi-gioi-thieu', [StoryController::class, 'update'])->name('story.update');
        Route::post('loi-gioi-thieu/upload-image', [StoryController::class, 'uploadImage'])->name('story.upload');

        Route::get('trang-chu', [AuthController::class, 'index'])->name('home');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::prefix('san-pham')->name('product.')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/them', [ProductController::class, 'create'])->name('create');
            Route::post('/them', [ProductController::class, 'store'])->name('store');
            Route::get('/cap-nhat/{product}', [ProductController::class, 'edit'])->name('edit');
            Route::put('/cap-nhat/{product}', [ProductController::class, 'update'])->name('update');
            Route::delete('/cap-nhat/{product}', [ProductController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('danh-muc')->name('category.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/them', [CategoryController::class, 'create'])->name('create');
            Route::post('/them', [CategoryController::class, 'store'])->name('store');
            Route::get('/cap-nhat/{category}', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/cap-nhat/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/cap-nhat/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('danh-muc-con')->name('sub-category.')->group(function () {
            Route::get('/', [SubCategoryController::class, 'index'])->name('index');
            Route::get('/them', [SubCategoryController::class, 'create'])->name('create');
            Route::post('/them', [SubCategoryController::class, 'store'])->name('store');
            Route::get('/cap-nhat/{subCategory}', [SubCategoryController::class, 'edit'])->name('edit');
            Route::put('/cap-nhat/{subCategory}', [SubCategoryController::class, 'update'])->name('update');
            Route::delete('/cap-nhat/{subCategory}', [SubCategoryController::class, 'destroy'])->name('destroy');
        });

        // news / events CRUD
        Route::prefix('tin-tuc')->name('news.')->group(function () {
            Route::get('/', [NewsController::class, 'index'])->name('index');
            Route::get('/them', [NewsController::class, 'create'])->name('create');
            Route::post('/them', [NewsController::class, 'store'])->name('store');
            Route::get('/cap-nhat/{news}', [NewsController::class, 'edit'])->name('edit');
            Route::put('/cap-nhat/{news}', [NewsController::class, 'update'])->name('update');
            Route::delete('/cap-nhat/{news}', [NewsController::class, 'destroy'])->name('destroy');
            Route::post('/upload-image', [NewsController::class, 'uploadImage'])->name('upload');
        });

        // service CRUD
        Route::prefix('dich-vu')->name('service.')->group(function () {
            Route::get('/', [AdminServiceController::class, 'index'])->name('index');
            Route::get('/them', [AdminServiceController::class, 'create'])->name('create');
            Route::post('/them', [AdminServiceController::class, 'store'])->name('store');
            Route::get('/cap-nhat/{service}', [AdminServiceController::class, 'edit'])->name('edit');
            Route::put('/cap-nhat/{service}', [AdminServiceController::class, 'update'])->name('update');
            Route::delete('/cap-nhat/{service}', [AdminServiceController::class, 'destroy'])->name('destroy');
        });

        // account settings
        Route::prefix('tai-khoan')->name('account.')->group(function () {
            Route::get('/', [AccountController::class, 'edit'])->name('edit');
            Route::put('/', [AccountController::class, 'update'])->name('update');
            Route::put('/mat-khau', [AccountController::class, 'updatePassword'])->name('password');
        });
        // company settings
        Route::prefix('cai-dat')->name('setting.')->group(function () {
            Route::get('/', [SettingController::class, 'edit'])->name('edit');
            Route::put('/', [SettingController::class, 'update'])->name('update');
        });

            // banner CRUD
            Route::prefix('banner')->name('banner.')->group(function () {
                Route::get('/', [BannerController::class, 'index'])->name('index');
                Route::get('/them', [BannerController::class, 'create'])->name('create');
                Route::post('/them', [BannerController::class, 'store'])->name('store');
                Route::get('/cap-nhat/{banner}', [BannerController::class, 'edit'])->name('edit');
                Route::put('/cap-nhat/{banner}', [BannerController::class, 'update'])->name('update');
                Route::delete('/cap-nhat/{banner}', [BannerController::class, 'destroy'])->name('destroy');
            });
    });
});
