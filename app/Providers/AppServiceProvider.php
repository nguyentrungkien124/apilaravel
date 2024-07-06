<?php

namespace App\Providers;

use App\Models\Dathang;
use App\Models\Giohang;
use App\Models\khachhang;
use Illuminate\Support\ServiceProvider;
use App\Models\Loaisp;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;

use function Ramsey\Uuid\v1;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $cats_home = Loaisp::orderBy('tenlsp', 'ASC')->where('trangthai', 1)->get();
            $giohangs = Giohang::where('khachhang_id', auth('cus')->id())->get();


            $userMoi = khachhang::count();
            $totalOrders = Dathang::count();
            $pendingOrders = Dathang::where('status', 0)->count();
            $confirmedOrders = Dathang::where('status', 1)->count();
            $goodOrders = Dathang::where('status', 2)->count();
            $canceledOrders = Dathang::where('status', 3)->count();

            // Thống kê doanh thu trong khoảng thời gian được chọn
            $startDate = request('start_date') ? Carbon::parse(request('start_date')) : null;
            $endDate = request('end_date') ? Carbon::parse(request('end_date'))->endOfDay() : null;
            $salesTotal = 0;

            if ($startDate && $endDate) {
                $salesTotal = Dathang::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 2) // Giả sử trạng thái 2 là đã thanh toán
                    ->with('details')
                    ->get()
                    ->sum(function ($order) {
                        return $order->details->sum(function ($detail) {
                            return $detail->gia * $detail->soluong;
                        });
                    });
            }

            $view->with(compact('cats_home','userMoi', 'giohangs', 'totalOrders', 'pendingOrders', 'confirmedOrders', 'goodOrders', 'canceledOrders',  'salesTotal'));
        });
    }
}
