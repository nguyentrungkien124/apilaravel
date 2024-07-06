<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HoadonbanController;
use App\Http\Controllers\Admin\LoaispController;
use App\Http\Controllers\Admin\SanphamController;
use App\Http\Controllers\Admin\slideController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\blogsController;
use App\Http\Controllers\Admin\hoadonnhapController;
use App\Http\Controllers\Admin\nguoidungController;
use App\Http\Controllers\DathangController;
use App\Http\Controllers\GiohangController;
use App\Http\Controllers\Admin\nhaphanphoiController;
use App\Models\Giohang;
use App\Models\Sanpham;

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

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/loaisp/{cat}', [HomeController::class, 'loaisp'])->name('home.loaisp');
Route::get('/sanpham/{sanpham}', [HomeController::class, 'sanpham'])->name('home.sanpham');
Route::get('/favorite/{sanpham}', [HomeController::class, 'favorite'])->name('home.favorite');
Route::get('/search', [HomeController::class, 'search'])->name('home.search'); // Route cho tìm kiếm
Route::get('/blogs', [HomeController::class, 'blogs'])->name('home.blogs');


Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'check_login']);

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/dathang', [HoadonbanController::class, 'index'])->name('dathang.index');
    Route::get('/dathang/detail/{dathang}', [HoadonbanController::class, 'detail'])->name('dathang.detail');
    Route::get('/dathang/update-status/{dathang}', [HoadonbanController::class, 'update'])->name('dathang.update');
    Route::post('/search', [SanphamController::class, 'search'])->name('search');


    Route::get('/hoadonnhap/detail/{hoadonnhap}', [hoadonnhapController::class, 'detail'])->name('hoadonnhap.detail');
    Route::resource('sanpham', SanphamController::class);
    Route::resource('loaisp', LoaispController::class);
    Route::resource('hoadonban', HoadonbanController::class);
    Route::resource('slide',slideController::class);
    Route::resource('hoadonnhap',hoadonnhapController::class);
    Route::resource('nhaphanphoi',nhaphanphoiController::class);
    Route::resource('nguoidung',nguoidungController::class);
    Route::resource('blogs',blogsController::class);
});

Route::group(['prefix'=>'account'],function(){
    Route::get('/login',[AccountController::class,'login'])->name('account.login');
    Route::get('/logout',[AccountController::class,'logout'])->name('account.logout');
    Route::get('/veryfy-account/{email}',[AccountController::class,'veryfy'])->name('account.veryfy');
    Route::post('/login',[AccountController::class,'check_login']);

    Route::get('/register',[AccountController::class,'register'])->name('account.register');
    Route::get('/favorite',[AccountController::class,'favorite'])->name('account.favorite');
    Route::post('/register',[AccountController::class,'check_register']);


Route::group(['middleware'=>'khachhang'],function(){
    Route::get('/profile',[AccountController::class,'profile'])->name('account.profile');
    Route::post('/profile',[AccountController::class,'check_profile']);

    Route::get('/change-password',[AccountController::class,'change_password'])->name('account.change_password');
    Route::post('/change-password',[AccountController::class,'check_change_password']);

});
    
    Route::get('/forgot-password',[AccountController::class,'forgot-password'])->name('account.forgot_password');
    Route::post('/forgot-password',[AccountController::class,'check_forgot_password']);
    
    Route::get('/reset-password',[AccountController::class,'reset-password'])->name('account.reset_password');
    Route::post('/reset-password',[AccountController::class,'check_reset_password']);
});
Route::group(['prefix'=>'giohang','middleware'=>'khachhang'],function(){
    
    Route::get('/login',[GiohangController::class,'index'])->name('giohang.index');
    Route::get('/add/{sanpham}',[GiohangController::class,'add'])->name('giohang.add');
    Route::get('/delete/{sanpham}',[GiohangController::class,'delete'])->name('giohang.delete');
    Route::get('/update/{sanpham}',[GiohangController::class,'update'])->name('giohang.update');
    Route::get('/clear',[GiohangController::class,'clear'])->name('giohang.clear');
    
});
Route::group(['prefix'=>'dathang','middleware'=>'khachhang'],function(){
    
    Route::get('/checkout',[DathangController::class,'checkout'])->name('dathang.checkout');
    Route::get('/lichsudh',[DathangController::class,'lichsudh'])->name('dathang.lichsudh');
    Route::get('/chitietdh/{dathang}',[DathangController::class,'chitietdh'])->name('dathang.chitietdh');
    Route::post('/checkout',[DathangController::class,'post_checkout']);
    
    Route::get('/verify/{token}',[DathangController::class,'verify'])-> name('dathang.verify');
});