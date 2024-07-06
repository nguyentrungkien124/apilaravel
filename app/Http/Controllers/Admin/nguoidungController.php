<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\khachhang;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class nguoidungController extends Controller
{
    public function index(){
        $nguoidungs = khachhang::all();
        return view('admin.nguoidung.index',compact('nguoidungs'));
    }
    
    
    public function create(){
        return view('admin.nguoidung.create');
    }
    
    public function store(Request $request){
        $request->validate([
            'tenkh'=>'required|min:4|max:150|',
            'password'=>'required',
            'email'=>'required',
            'diachi'=>'required|min:4|max:150|',
            'sodt'=>'required|min:4|max:150|'
        ]);
        $khachhangData = $request->only( 'tenkh','password','email','diachi','sodt','ghichu');
        // dd($khachhangData);
        if(khachhang::create($khachhangData)){
            
            return redirect()->route('nguoidung.index')->with('ok','Thêm khách hàng thành công');
        }
        return redirect()->back()->with('no','Thêm thất bại');
    }
    public function destroy($id){
        $khachhang =khachhang::findOrFail($id);
        if($khachhang->delete()){
            return redirect()->route('nguoidung.index')->with('ok','Xóa người dùng thành công');
        }
        return redirect()->back()->with('no','không thành công');
    }
}
