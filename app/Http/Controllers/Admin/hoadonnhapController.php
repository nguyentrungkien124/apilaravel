<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chitiethoadonnhap;
use App\Models\Hoadonnhap;
use App\Models\Nhaphanphoi;
use App\Models\Sanpham;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class hoadonnhapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $hoadonnhap = Hoadonnhap::all();
        return response()->json($hoadonnhap);
    }

    // public function detail($id)
    // {
    //     $hoadonnhap = Hoadonnhap::with('chitiets.sanpham')->find($id);
    //     // dd($hoadonnhap);
    //     return view('admin.hoadonnhap.detail', compact('hoadonnhap'));
    // }



    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $currentUser = Auth::user();
    //     $matk = Nhaphanphoi::all();
    //     $nhaps = Sanpham::orderBy('tensp', 'ASC')->select('id', 'tensp')->get();
    //     return view('admin.hoadonnhap.create', compact('nhaps', 'matk', 'currentUser'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'maNhaPhanPhoi' => 'required',
            'kieuthanhtoan' => 'required',
            'maTaiKhoan' => 'required',
            'tongtien' => 'required',
            'chitiets.*.sanpham_id' => 'required',
            'chitiets.*.soLuong' => 'required',
            'chitiets.*.giaNhap' => 'required',
        ]);
        if ($validator->fails()) {
            $arr = [
                'success' => false,
                'message' => 'lỗi kiểm tra lại dữ liệu',
                'data' => $validator->errors()
            ];
            return response()->json($arr, 200);
        }
        DB::transaction(function () use ($input) {
            $hoadonnhap = Hoadonnhap::create([
                'maNhaPhanPhoi' => $input['maNhaPhanPhoi'],
                'kieuthanhtoan' => $input['kieuthanhtoan'],
                'maTaiKhoan' => $input['maTaiKhoan'],
                'tongtien' => $input['tongtien'],
            ]);
            foreach ($input['chitiets'] as $chitiets) {
                Chitiethoadonnhap::create([
                    'maHoaDon' => $hoadonnhap->id,
                    'sanpham_id' => $chitiets['sanpham_id'],
                    'soLuong' => $chitiets['soLuong'],
                    'giaNhap' => $chitiets['giaNhap'],
                ]);
            };
        });
        // $hoadonnhap = Hoadonnhap::create($input);
        $arr = [
            'status' => true,
            'message' => "Hóa đơn nhập đã lưu thành công",
            // 'data'=>$hoadonnhap
        ];
        return response()->json($arr, 201);
        // Kiểm tra hợp lệ
        // $validatedData = $request->validate([
        //     'maNhaPhanPhoi' => 'required|integer',
        //     'kieuthanhtoan' => 'required|string',
        //     'maTaiKhoan' => 'required|integer',
        //     'tongtien' => 'required|numeric',
        //     'chitiets.*.sanpham_id' => 'required|integer',
        //     'chitiets.*.soLuong' => 'required|integer',
        //     'chitiets.*.giaNhap' => 'required|numeric',
        // ]);

        // // Tạo hóa đơn nhập
        // $hoadonnhap = Hoadonnhap::create([
        //     'maNhaPhanPhoi' => $request->maNhaPhanPhoi,
        //     'kieuthanhtoan' => $request->kieuthanhtoan,
        //     'maTaiKhoan' => $request->maTaiKhoan,
        //     'tongtien' => 0, // Khởi tạo tổng tiền bằng 0
        // ]);

        // // Lưu chi tiết hóa đơn nhập và cập nhật số lượng sản phẩm
        // $tongtien = 0;
        // foreach ($request->chitiets as $chitiet) {
        //     $sanpham = Sanpham::find($chitiet['sanpham_id']);
        //     if ($sanpham) {
        //         // Cập nhật số lượng sản phẩm
        //         $sanpham->soluong += $chitiet['soLuong'];
        //         $sanpham->gia += $chitiet['giaNhap'] * 1.2;
        //         $sanpham->save();

        //         // Lưu chi tiết hóa đơn nhập
        //         $chitiethoadonnhap = Chitiethoadonnhap::create([
        //             'maHoaDon' => $hoadonnhap->id,
        //             'sanpham_id' => $chitiet['sanpham_id'],
        //             'soLuong' => $chitiet['soLuong'],
        //             'giaNhap' => $chitiet['giaNhap'],
        //             // Không cần lưu 'tongtien' vì đã là cột GENERATED
        //         ]);

        //         // Cộng dồn tổng tiền
        //         $tongtien += $chitiet['soLuong'] * $chitiet['giaNhap'];
        //     }
        // }

        // // Cập nhật tổng tiền hóa đơn nhập
        // $hoadonnhap->tongtien = $tongtien;
        // $hoadonnhap->save();

        // return redirect()->route('hoadonnhap.index')->with('ok', 'Hóa đơn nhập đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(hoadonnhap $hoadonnhap)
    {
        // Tìm hóa đơn nhập và tải các chi tiết liên quan
        $hoadonnhap = Hoadonnhap::with('chitiets')->find($hoadonnhap);

        if (is_null($hoadonnhap)) {
            $arr = [
                'success' => false,
                'message' => 'Không tìm thấy hóa đơn này',
                'data' => []
            ];
            return response()->json($arr, 200);
        }

        $arr = [
            'success' => true,
            'message' => 'Chi tiết hóa đơn',
            'data' => $hoadonnhap
        ];
        return response()->json($arr, 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //return view('admin.loaisp.edit',compact('loaisp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, hoadonnhap $hoadonnhap)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'maNhaPhanPhoi' => 'required',
            'kieuthanhtoan' => 'required',
            'maTaiKhoan' => 'required',
            'tongtien' => 'required',
            'chitiets.*.sanpham_id' => 'required',
            'chitiets.*.soLuong' => 'required',
            'chitiets.*.giaNhap' => 'required',
        ]);
        if ($validator->fails()) {
            $arr = [
                'success' => false,
                'message' => 'Lỗi kiểm tra lại',
                'data' => $validator->errors()
            ];
            return response()->json($arr, 200);
        }
        DB::transaction(function () use ($hoadonnhap, $input) {
            $hoadonnhap->update([
                'maNhaPhanPhoi' => $input['maNhaPhanPhoi'],
                'kieuthanhtoan' => $input['kieuthanhtoan'],
                'maTaiKhoan' => $input['maTaiKhoan'],
                'tongtien' => $input['tongtien']
            ]);
            $hoadonnhap->chitiets()->delete();
            foreach ($input['chitiets'] as $chitiets){
                Chitiethoadonnhap::create([
                    'maHoaDon' => $hoadonnhap->id,
                    'sanpham_id' => $chitiets['sanpham_id'],
                    'soLuong' => $chitiets['soLuong'],
                    'giaNhap' => $chitiets['giaNhap'],
                ]);

            }
        });
        $hoadonnhap->load('chitiets');
        return response()->json([
            'success'=> true,
            'message'=>'Sửa thành công',
            'data'=> $hoadonnhap
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(hoadonnhap $hoadonnhap)
    {
      
            $hoadonnhap->chitiets()->delete(); // Xóa các chi tiết hóa đơn trước
            $hoadonnhap->delete(); // Xóa hóa đơn nhập

    
        $arr = [
            'status' => true,
            'message' => 'Hóa đơn đã được xóa',
            'data' => []
        ];
        return response()->json($arr, 200);
    }
}
