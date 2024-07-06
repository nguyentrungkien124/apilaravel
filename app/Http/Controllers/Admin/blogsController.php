<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blogs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Validators;

class BlogsController extends Controller
{
    public function index()
    {
        $blogs = Blogs::with('user')->get();
        return response()->json($blogs);
    }

    // public function create(){
    //     $nguoidang = User::orderBy('name', 'ASC')->select('id', 'name')->get();
    //     return view('admin.blogs.create', compact('nguoidang'));
    // }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'content_title' => 'required',
            'user_id' => 'required',
            'hinhanh' => '',
            'ghichu' => 'required'
        ]);
        if($validator->fails()){
            $arr = [
                'success'=>false,
                'message'=>'Lỗi kiểm tra lại dữ liệu',
                'data'=>$validator->errors()
            ];
            return response()->json($arr,200);
        }
        $blogs = Blogs::create($input);
        $arr = [
            'status'=>true,
            'message'=>"Blogs đã lưu thành công",
            'data'=>$blogs
        ];
        return response()->json($arr,201);
    }

    public function show($id)
    {
        $blogs = Blogs::find($id);
       if(is_null($blogs)){
        $arr = [
            'success'=> false,
            'message'=>'không tìm thấy blogs này',
            'data'=>[]
        ];
        return response()->json($arr,200);
       }
       $arr = [
        'success'=>true,
        'message'=>'Chi tiết blogs',
        'data'=>$blogs
       ];
       return response()->json($arr,201);
    }

    public function update(Request $request, blogs $blogs)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'title' => 'required',
            'content_title' => 'required',
            'user_id' => 'required',
            'hinhanh' => '',
            'ghichu' => 'required'
        ]);
        if($validator->fails()){
            $arr = [
                'success'=> false,
                'message'=> 'Lỗi kiểm tra lại',
                'data'=>$validator->errors()
            ];
            return response()->json($arr,200);
        }
        $blogs->title = $input['title'];
        $blogs->content_title = $input['content_title'];
        $blogs->user_id = $input['user_id'];
        $blogs->hinhanh = $input['hinhanh'];
        $blogs->ghichu = $input['ghichu'];
        $blogs->save();
        $arr = [
            'status'=>true,
            'message' =>'Blogs cập nhật thành công',
            'data'=>$blogs
        ];
        return response()->json($arr,200);
        // $request->validate([
        //     'title' => 'required|unique:blogs,title,' . $id,
        //     'content_title' => 'required',
        //     'user_id' => 'required',
        //     'hinhanh' => 'file|mimes:jpg,png,gif,jpeg',
        //     'ghichu' => 'required'
        // ]);

        // $blogsData = $request->only('title', 'content_title', 'user_id', 'ghichu');
        // $blog = Blogs::findOrFail($id);

        // if ($request->hasFile('hinhanh')) {
        //     $imageName = $request->hinhanh->hashName();
        //     $request->hinhanh->move(public_path('/uploads/images/'), $imageName);
        //     $blogsData['hinhanh'] = $imageName;
        // }

        // if ($blog->update($blogsData)) {
        //     return redirect()->route('blogs.index')->with('ok', 'Cập nhật thành công blogs');
        // }

        // return redirect()->back()->with('no', 'Cập nhật thất bại');
    }

    public function destroy(blogs $blogs)
    {
        $blogs->delete();
        $arr =[
            'status' => true,
            'message'=>'Blogs đã được xóa',
            'data' =>[]
        ];
        return response()->json($arr,200);

        
    }
}
