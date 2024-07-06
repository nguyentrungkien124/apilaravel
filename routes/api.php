<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BlogsController;
use App\Http\Controllers\Admin\hoadonnhapController;
use App\Http\Controllers\Admin\loaispController;
use App\Http\Controllers\Admin\nhaphanphoiController;
use App\Http\Controllers\Admin\sanphamController;
use App\Http\Controllers\Admin\slideController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\GiohangController;
use App\Models\Loaisp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// API SẢN PHẨM
Route::prefix('sanpham')->group(function() {
    Route::get('/', [sanphamController::class, 'index']);
    Route::get('/search', [sanphamController::class, 'search']);
    Route::post('/', [sanphamController::class, 'store']);
    Route::get('/{sanpham}', [sanphamController::class, 'show']);
    Route::put('/{sanpham}', [sanphamController::class, 'update']);
    Route::delete('/{sanpham}', [sanphamController::class, 'destroy']);
    Route::get('/loai/{idloai}', [sanphamController::class, 'loai']);
    Route::get('/sort/price', [SanphamController::class, 'getSanphamSortedByPrice']);
});
// API upload ảnh
Route::post('/upload-image', [ImageController::class, 'uploadImage']);
// API LOẠI SẢN PHẨM
Route::prefix('loaisp')->group(function() {
    Route::get('/', [loaispController::class, 'index']);
    Route::post('/', [loaispController::class, 'store']);
    Route::get('/{loaisp}', [loaispController::class, 'show']);
    Route::put('/{loaisp}', [loaispController::class, 'update']);
    Route::delete('/{loaisp}', [loaispController::class, 'destroy']);
});

// API ĐĂNG NHẬP

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AdminController::class,'login']);
    Route::post('logout',  [AdminController::class,'logout']);
    Route::post('refresh', [AdminController::class,'refresh']);
    Route::get('profile', [AdminController::class,'profile']);
    Route::post('register', [AdminController::class,'register']);

});
// API hóa đơn nhập
Route::prefix('hoadonnhap')->group(function() {
    Route::get('/', [hoadonnhapController::class, 'index']);
    Route::post('/', [hoadonnhapController::class, 'store']);
    Route::get('/{hoadonnhap}', [hoadonnhapController::class, 'show']);
    //  Route::get('/{chitiethoadonnhap}', [hoadonnhapController::class, 'show1']);
    Route::put('/{hoadonnhap}', [hoadonnhapController::class, 'update']);
    Route::delete('/{hoadonnhap}', [hoadonnhapController::class, 'destroy']);
});

//API blogs
Route::prefix('blogs')->group(function() {
    Route::get('/', [BlogsController::class, 'index']);
    Route::post('/', [BlogsController::class, 'store']);
    Route::get('/{blogs}', [BlogsController::class, 'show']);
    //  Route::get('/{chitietblogs}', [blogsController::class, 'show1']);
    Route::put('/{blogs}', [BlogsController::class, 'update']);
    Route::delete('/{blogs}', [BlogsController::class, 'destroy']);
});


//API nhà phân phối 
Route::prefix('nhaphanphoi')->group(function() {
    Route::get('/', [nhaphanphoiController::class, 'index']);
    Route::post('/', [nhaphanphoiController::class, 'store']);
    Route::get('/{nhaphanphoi}', [nhaphanphoiController::class, 'show']);
    //  Route::get('/{chitietnhaphanphoi}', [nhaphanphoiController::class, 'show1']);
    Route::put('/{nhaphanphoi}', [nhaphanphoiController::class, 'update']);
    Route::delete('/{nhaphanphoi}', [nhaphanphoiController::class, 'destroy']);
});

//  API slide
Route::prefix('slide')->group(function() {
    Route::get('/', [slideController::class, 'index']);
    Route::post('/', [slideController::class, 'store']);
    Route::get('/{slide}', [slideController::class, 'show']);
    //  Route::get('/{chitietslide}', [slideController::class, 'show1']);
    Route::put('/{slide}', [slideController::class, 'update']);
    Route::delete('/{slide}', [slideController::class, 'destroy']);
});


// USER


// API GIỎ HÀNG
Route::prefix('giohang')->group(function() {
    Route::get('/', [GiohangController::class, 'index']);
    Route::post('/', [GiohangController::class, 'store']);
    Route::get('/{giohang}', [GiohangController::class, 'show']);
    Route::get('/khachhang/{khachhang_id}', [GiohangController::class, 'findByKhachhangId']);
    Route::put('/{giohang}', [GiohangController::class, 'update']);
    Route::delete('/{giohang}', [GiohangController::class, 'destroy']);
});






