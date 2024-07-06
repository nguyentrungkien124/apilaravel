<?php

namespace App\Http\Controllers;

use App\Models\Sanpham;
use App\Models\Giohang;
use App\Models\khachhang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GiohangController extends Controller
{
    public function index()
    {
        // $giohangs = Giohang::where('khachhang_id',auth('cus')->id())->get();
        return response()->json(Giohang::with('prod')->get());
    }
    public function search(){
        $khachang = khachhang::all();
    }
    public function store( Request $request)
    {
        $validated = $request->validate([
            'sanpham_id' => 'required|exists:sanpham,id',
            'khachhang_id' => 'required|exists:khachhang,id',
            'soluong' => 'required|integer|min:1',
            'gia' => 'required|numeric|min:0',
        ]);

        $giohang = Giohang::create($validated);
        return response()->json($giohang, 201);
    }

    public function update(Sanpham $sanpham, Request $req)
    {
        $soluong = $req->soluong ? floor($req->soluong) : 1;
        $kh_id = auth('cus')->id();
        $giohangExist = Giohang::where([
            'khachhang_id' =>  $kh_id,
            'sanpham_id' => $sanpham->id
        ])->first();

        if ($giohangExist) {
            Giohang::where([
                'khachhang_id' =>  $kh_id,
                'sanpham_id' => $sanpham->id
            ])->update([
                'soluong' => $soluong
            ]);

            return redirect()->route('giohang.index')->with('ok', 'Sửa số lượng sản phẩm thành công');
        }
        

        return redirect()->back()->with('no', 'Lỗi');
    }

    public function delete($sanpham_id)
    {
        $kh_id = auth('cus')->id();
        Giohang::where([
            'khachhang_id' =>  $kh_id,
            'sanpham_id' => $sanpham_id
        ])->delete();
        return redirect()->route('giohang.index')->with('ok', 'Xóa thành công');
    }
    public function clear()
    {
        $kh_id = auth('cus')->id();
        Giohang::where([
            'khachhang_id' =>  $kh_id
        ])->delete();
        return redirect()->back()->with('ok', 'Xóa thành công');
    }
    public function findByKhachhangId($khachhang_id)
    {
        $giohangs = Giohang::with('prod')->where('khachhang_id', $khachhang_id)->get();

        if ($giohangs->isEmpty()) {
            return response()->json(['message' => 'Không tìm thấy giỏ hàng cho khách hàng này'], 404);
        }
        $arr =[
            'success'=>true,
            'message'=> 'khách hàng có giỏ hàng',
            'data'=>$giohangs

        ];

        return response()->json($arr,201);
    }

    // Hiển thị giỏ hàng theo id
    // public function show($id)
    // {
    //     $giohang = Giohang::with('prod')->findOrFail($id);
    //     return response()->json($giohang);
    // }

}


// public function update(Request $request, $id)
//     {
//         $validated = $request->validate([
//             'soluong' => 'required|integer|min:1',
//         ]);

//         $giohang = Giohang::findOrFail($id);
//         $giohang->update($validated);

//         return response()->json($giohang);
//     }

//     // Xóa sản phẩm khỏi giỏ hàng
//     public function destroy($id)
//     {
//         $giohang = Giohang::findOrFail($id);
//         $giohang->delete();

//         return response()->json(null, 204);
//     }
// }