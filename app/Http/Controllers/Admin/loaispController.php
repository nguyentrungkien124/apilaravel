<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\loaisp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class loaispController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loaisp = Loaisp::all();
        return response()->json($loaisp);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('admin.loaisp.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
             'tenlsp' => 'required|min:4|max:150|unique:loaisp'
        ]);
        if($validator->fails()){
            $arr = [
                'success' => false,
                'message' => 'Lỗi kiểm tra dữ liệu',
                'data'=>$validator->errors()
            ];
            return response()->json($arr,200);
        }
        $loaisp = loaisp::create($input);
        $arr = [
            'status'=> true,
            'message'=>"Loại sản phẩm lưu thành công",
            'data'=> $loaisp
        ];
        return response()->json($arr,201);
        // $request->validate([
        //     'tenlsp' => 'required|min:4|max:150|unique:loaisp'
        // ]);
        // $loaispData = $request->only('tenlsp');

        // if(Loaisp::create($loaispData)){
        //     return redirect()->route('loaisp.index')->with('ok','thêm thành công');

        // }
        // return redirect()->back()->with('no','không thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(loaisp $loaisp)
    {
        $loaisp = loaisp::find($loaisp);
        if(is_null($loaisp)){
            $arr = [
                'success'=>false,
                'message'=>'không tìm thấy loại sản phẩm này',
                'data'=>[]
            ];
            return response()->json($arr,200);
        }
        $arr = [
            'success'=>true,
            'message'=> 'chi tiết loai sản phẩm',
            'data'=>$loaisp
        ];
        return response()->json($arr,201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(loaisp $loaisp)
    // {
    //     return view('admin.loaisp.edit',compact('loaisp'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, loaisp $loaisp)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
             'tenlsp'=> 'required|min:4|max:150|unique:loaisp'
        ]);
        if($validator->fails()){
            $arr = [
                'success'=>false,
                'message'=>'Lỗi kiểm tra lại',
                'data'=>$validator->errors()
            ];
            return response()->json($arr,200);
        }
        $loaisp->tenlsp=$input['tenlsp'];
        $loaisp->save();
        $arr = [
            'status'=>true,
            'message'=> 'Loại sản phẩm được cập nhật thành công',
            'data'=>$loaisp
        ];
        return response()->json($arr,200);
        // $request->validate([
        //     'tenlsp'=> 'required|min:4|max:150|unique:loaisp'
        // ]);
        // $loaispData=[
        //     'tenlsp' => $request-> tenlsp
        // ];
        
        // if ($loaisp->update($loaispData)){
        //     return redirect()->route('loaisp.index')->with('ok','cập nhật thành công');

        // }
        // return redirect()->back()->with('no','cập nhật không thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(loaisp $loaisp)
    {   
        $loaisp->delete();
        $arr = [
            'status'=>true,
            'message'=> 'Loại sản phẩm đã được xóa',
            'data'=>[]
        ];
        return response()->json($arr,200);
        // if ($loaisp->delete()){
        //     return redirect()->route('loaisp.index')->with('ok','xóa thành công');
        // }
        // return redirect()->back()->with('no','không thành công');
    }
}
