<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class slideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slide = Slide::all();
        return response()->json($slide);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.slide.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'tenslide' => 'required',
            'image' => '',
            'gia' => 'required'
        ]);
        if ($validator->fails()) {
            $arr = [
                'success' => false,
                'message' => 'Lỗi kiểm tra dữ liệu',
                'data' => $validator->errors()
            ];
            return response()->json($arr, 200);
        }
        $slide = slide::create($input);
        $arr = [
            'status' => true,
            'message' => 'Slide lưu thành công',
            'data' => $slide
        ];
        return response()->json($arr, 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(slide $slide)
    {
        $slide = slide::find($slide);
        if(is_null($slide)){
            $arr = [
                'success'=>false,
                'message'=>'Không thấy sản phẩm',
                'data'=>[]
            ];
            return response()->json($arr,200);
        }
        $arr = [
            'success'=> true,
            'message'=>'chi tiết sản phẩm',
            'data'=>$slide
        ];
        return response()->json($arr,201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(slide $slide)
    // {
    //     return view('admin.slide.edit', compact('slide'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, slide $slide)
    {

        $input = $request->all();
        $validator = Validator::make($input,[
            'tenslide' => 'required',
            'image' => '',
            'gia' => 'required'
        ]);
        if($validator->fails()){
            $arr = [
                'success'=>false,
                'message'=>'Lỗi dữ liệu',
                'data'=> $validator->errors()
            ];
            return response()->json($arr,200);
        }
        $slide->tenslide=$input['tenslide'];
        $slide->image=$input['image'];
        $slide->gia=$input['gia'];
        $slide->save();
        $arr=[
            'status'=>true,
            'message'=>'Slide cập nhật thành công',
            'data'=>$slide
        ];
        return response()->json($arr,201);
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(slide $slide)
    {
        $slide->delete();
        $arr = [
            'status'=> true,
            'message'=>'slide đã được xóa thành công',
            'data'=>[]
        ];
        return response()->json($arr,200);
    }
}
