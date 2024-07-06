<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loaisp;
use App\Models\sanpham;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class sanphamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sanpham = Sanpham::all();
        return response()->json($sanpham);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        // Lọc danh sách sản phẩm theo từ khóa tìm kiếm
        $sanpham = Sanpham::where('tensp', 'like', "%{$searchTerm}%")->paginate(10);

        return response()->json($sanpham);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'tensp' => 'required',
            'tenhang' => 'required',
            'gia' => 'required',
            'giakm' => 'required',
            'hinhanh' => '',
            'id_loai' => 'required',
            'soluong' => 'required',
            'mota' => 'required',
        ]);
        if ($validator->fails()) {
            $arr = [
                'success' => false,
                'message' => 'Lỗi kiểm tra dữ liệu',
                'data' => $validator->errors()
            ];
            return response()->json($arr, 200);
        }
        $sanpham = sanpham::create($input);
        $arr = [
            'status' => true,
            'message' => "Sản phẩm đã lưu thành công",
            'data' => $sanpham
        ];
        return response()->json($arr, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(sanpham $sanpham)
    {
        $sanpham = sanpham::find($sanpham);
        if(is_null($sanpham)){
            $arr = [
                'success' => false,
                'message'=> ' không thấy sản phẩm này',
                'data'=>[]
            ];
            return response()-> json($arr,200);
        }
        $arr = [
            'success' => true,
            'message'=> ' Chi tiết sản phẩm',
            'data'=>$sanpham
        ];
        return response()-> json($arr,201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sanpham $sanpham)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'tensp' => 'required',
            'tenhang'   => 'required',
            'gia' => 'required',
            'giakm' => 'required',
            'hinhanh' => '',
            'id_loai' => 'required',
            'soluong' => 'required',
            'mota' => 'required',
        ]);
        if ($validator->fails()) {
            $arr = [
                'success' => false,
                'message' => 'Lỗi kiểm tra lại',
                'data' => $validator->errors()
            ];
            return response()->json($arr, 200);
        }
        $sanpham->tensp = $input['tensp'];
        $sanpham->tenhang = $input['tenhang'];
        $sanpham->gia = $input['gia'];
        $sanpham->giakm = $input['giakm'];
        $sanpham->hinhanh = $input['hinhanh'];
        $sanpham->id_loai = $input['id_loai'];
        $sanpham->soluong = $input['soluong'];
        $sanpham->mota = $input['mota'];
        $sanpham->save();
        $arr = [
            'status' => true,
            'message' => 'Sản phẩm cập nhật thành công',
            'data' => $sanpham
        ];
        return response()->json($arr, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sanpham $sanpham)
    {
        $sanpham->delete();
        $arr = [
            'status'=>true,
            'message'=>'Sản phẩm đã được xóa',
            'data'=> []
        ];
        return response()->json($arr, 200);
    }
    public function loai($categoryId)
    {
        $sanpham = Sanpham::where('id_loai', $categoryId)->limit(12)->get();
        return response()->json($sanpham);
    }
    public function getSanphamSortedByPrice(Request $request)
    {
        $order = $request->query('order', 'desc'); // Mặc định là 'desc' nếu không có tham số 'order'
        
        try {
            $sanphams = Sanpham::orderBy('giakm', $order)->get();
            return response()->json($sanphams, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi trong quá trình lấy sản phẩm'], 500);
        }
    }
}
