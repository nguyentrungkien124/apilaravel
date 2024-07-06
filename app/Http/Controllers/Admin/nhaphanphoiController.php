<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\nhaphanphoi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class nhaphanphoiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nhaphanphoi = Nhaphanphoi::all();
        return response()->json($nhaphanphoi);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.nhaphanphoi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'TenNPP' => 'required',
            'DiaChi' => 'required',
            'Email' => 'required',
            'SoDienThoai' => 'required'
        ]);
        if ($validator->fails()) {
            $arr = [
                'success' => false,
                'message' => 'Lỗi kiểm tra dữ liệu',
                'data' => $validator->errors()
            ];
            return response()->json($arr, 201);
        }
        $nhaphanphoi = nhaphanphoi::create($input);
        $arr = [
            'status' => true,
            'message' => 'Nhà phân phối lưu thành công',
            'data' => $nhaphanphoi
        ];
        return response()->json($arr, 201);

        //  $request->validate([
        //     'TenNPP' => 'required|min:4|max:150|unique:nhaphanphoi',
        //     'DiaChi' => 'required|min:4|max:150|',
        //     'Email' => 'required',
        //     'SoDienThoai' => 'required|min:4|max:150|'

        // ]);
        //  $nhaphanphoiData = $request->only('TenNPP','DiaChi','Email','SoDienThoai');

        //  if(Nhaphanphoi::create($nhaphanphoiData)){
        //     return redirect()->route('nhaphanphoi.index')->with('ok','thêm thành công');

        // }
        //  return redirect()->back()->with('no','không thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(nhaphanphoi $nhaphanphoi)
    {
        $nhaphanphoi = nhaphanphoi::find($nhaphanphoi);
        if (is_null($nhaphanphoi)) {
            $arr = [
                'success' => false,
                'message' => 'không tìm thấy nhà phân phối',
                'data' => []
            ];
            return response()->json($arr, 200);
        }
        $arr = [
            'success' => true,
            'message' => 'chi tiết nhà phân phối',
            'data' => $nhaphanphoi
        ];
        return response()->json($arr, 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(nhaphanphoi $nhaphanphoi)
    // {
    //     return view('admin.nhaphanphoi.edit',compact('nhaphanphoi'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, nhaphanphoi $nhaphanphoi)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'TenNPP' => 'required',
            'DiaChi' => 'required',
            'Email' => 'required',
            'SoDienThoai' => 'required'
        ]);
        if($validator->fails()){
            $arr =[
                'success'=>false,
                'message'=>'Lỗi kiểm tra lại',
                'data'=> $validator->errors()
            ];
            return response()->json($arr,200);
        }
        $nhaphanphoi->TenNPP=$input['TenNPP'];
        $nhaphanphoi->DiaChi=$input['DiaChi'];
        $nhaphanphoi->Email=$input['Email'];
        $nhaphanphoi->SoDienThoai=$input['SoDienThoai'];
        $nhaphanphoi->save();
        $arr = [
            'status'=>true,
            'message'=>'nhà phân phối cập nhật thành công',
            'data'=>$nhaphanphoi
        ];
        return response()->json($arr,200);
        // $request->validate([
        //     'TenNPP' => 'required|min:4|max:150|unique:nhaphanphoi',
        //     'DiaChi' => 'required|min:4|max:150|',
        //     'Email' => 'required',
        //     'SoDienThoai' => 'required|min:4|max:150|'
        // ]);
        // $nhaphanphoiData=[
        //     'TenNPP' => $request-> TenNPP,
        //     'DiaChi' => $request-> DiaChi,
        //     'Email' => $request-> Email,
        //     'SoDienThoai' => $request-> SoDienThoai
        // ];

        // if ($nhaphanphoi->update($nhaphanphoiData)){
        //     return redirect()->route('nhaphanphoi.index')->with('ok','cập nhật thành công');

        // }
        // return redirect()->back()->with('no','cập nhật không thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(nhaphanphoi $nhaphanphoi)
    {
        $nhaphanphoi->delete();
        $arr=[
            'status'=>true,
            'message'=>'Nhà phân phối đã được xóa',
            'data'=>[]
        ];
        return response()->json($arr,200);
       
    }
}
