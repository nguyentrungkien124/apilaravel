<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chitietdathang;
use App\Models\Dathang;
use App\Models\Sanpham;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


class AdminController extends Controller
{
    // public function index(Request $request)

    // {
    //     $salesTotal = 0;

    //     // Kiểm tra xem có dữ liệu ngày bắt đầu và ngày kết thúc hay không
    //     if ($request->has(['start_date', 'end_date'])) {
    //         $startDate = Carbon::parse($request->input('start_date'));
    //         $endDate = Carbon::parse($request->input('end_date'))->endOfDay(); // Kết thúc ngày

    //         // Tính tổng doanh thu trong khoảng thời gian được chọn
    //         $salesTotal = Dathang::whereBetween('created_at', [$startDate, $endDate])
    //             ->where('status', 2) // Giả sử trạng thái 2 là đã thanh toán
    //             ->with('details')
    //             ->get()
    //             ->sum(function ($order) {
    //                 return $order->details->sum(function ($detail) {
    //                     return $detail->gia * $detail->soluong;
    //                 });
    //             });
    //     }

    //    // Lấy dữ liệu sản phẩm bán chạy với số lượng mua lớn hơn 2
    //    $products = Sanpham::with('cat')
    //    ->withCount('chitietdathangs as total_sold')
    //    ->get()
    //    ->filter(function ($product) {
    //        return $product->total_sold > 2; // Lọc các sản phẩm bán được hơn 2 đơn vị
    //    })
    //    ->sortByDesc('total_sold') // Sắp xếp giảm dần theo số lượng bán
    //    ->take(10); // Lấy 10 sản phẩm bán chạy nhất


    //     return view('admin.index',compact('products','salesTotal'));
    // }

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'lỗi ok'], 401);
        }
      

        $refreshToken = $this->createRefreshToken();
        return $this->respondWithToken($token, $refreshToken);
    }
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
    private function respondWithToken($token, $refreshToken)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    // lấy thông tin user
    public function profile()
    {
        try {

            return response()->json(auth('api')->user());
        } catch (JWTException $exception) {
            return response()->json(['error' => 'lỗi'], 401);
        }
    }
    public function refresh()
    {
        $refreshToken = request()->refresh_token;
        try {

            $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);
            //  xử lý cấp lại token mới
            // -> lấy thông tin users

            $user = User::find($decoded['user_id']);
            if (!$user) {
                return response()->json(['error' => "không tồn tại user"], 404);
            }
            auth('api')->invalidate(); //vô hiệu hóa token hiện tại

            $token = auth('api')->login($user); // Tạo token mới 
            $refreshToken =  $this->createRefreshToken();
            return $this->respondWithToken($token, $refreshToken);
        } catch (JWTException $exception) {
            return response()->json(['error' => 'lỗi'], 500);
        }
        // return $this->respondWithToken(auth('api')->refresh());
    }
    private function createRefreshToken()
    {

        $data = [
            'user_id' => auth('api')->user()->id,
            'random' => rand() . time(),
            'exp' => time() + config('jwt.refresh_ttl')
        ];
        $refreshToken = JWTAuth::getJWTProvider()->encode($data);
        return $refreshToken;
    }
  
}
