<?php

namespace App\Http\Controllers;

use App\Mail\OrderMail;
use App\Models\Chitietdathang;
use App\Models\Dathang;
use App\Models\Sanpham;
use Illuminate\Http\Request;
use Mail;
class DathangController extends Controller
{
    public function checkout(){
        $auth = auth('cus')->user();
        return 
        view('home.checkout',compact('auth'));
    }
    public function lichsudh(){
        $auth = auth('cus')->user();
        return view('home.lichsudh',compact('auth'));
    }
    public function chitietdh(Dathang $dathang){
        $auth = auth('cus')->user();
        return view('home.chitietdh',compact('auth','dathang'));
    }

    public function post_checkout(Request $req){
        $auth = auth('cus')->user();
        
        $req->validate([
            'tenkh' => 'required',
            'email' => 'required|email',
            'sodt' => 'required',
            'diachi' => 'required',
        // ], [
        //     'tenkh.required' => 'Họ tên không được để trống',
        //     'tenkh.min' => 'Họ tên tối thiểu 6 kí tự',

        ]);

        $data = $req->only('tenkh', 'email', 'sodt', 'diachi');
        $data['khachhang_id'] = $auth->id;
        if ($dathang = Dathang::create($data)) {
            $token = \Str::random(40);
            // carts đc lấy từ model khách hàng
            foreach ($auth->carts as $cart) {
                $sanpham = Sanpham::find($cart->sanpham_id);
                $sanpham->soluong -= $cart->soluong;
                $sanpham->save();
                $data1 = [
                    'dathang_id' => $dathang->id,
                    'sanpham_id' => $cart->sanpham_id,
                    'gia' => $cart->gia,
                    'soluong' => $cart->soluong
                ];
                Chitietdathang::create($data1);
            }
            // Gửi email xác nhận
            // Lưu token vào dathang và lưu dathang
            $dathang->token = $token;
            $dathang->save();
            Mail::to($auth->email)->send(new OrderMail($dathang, $token));
            return redirect()-> route('home.index')->with('ok','Đặt hàng thành công vui lòng kiểm tra email');
        
        }
        return redirect()-> route('home.index')->with('no',' Lỗi đặt hàng');
   
    }
    public function verify($token){
        $dathang = Dathang::where('token',$token)-> first();
        if ($dathang) {
            $dathang->token = null;
            $dathang->status = 1;
            $dathang->save();
            return redirect()-> route('home.index')->with('ok','Đặt hàng thành công');
        }
        return abort(404);
    }
}
