<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dathang;
use App\Models\hoadonban;
use Illuminate\Http\Request;

class hoadonbanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = request('status','1');
        $dathangs = Dathang::orderBy('id','DESC')->where('status',$status)->paginate();
        return view('admin.dathang.index',compact('dathangs'));
    }
    public function detail(Dathang $dathang){
        $auth= $dathang->khachhang ;
        return view('admin.dathang.detail',compact('auth','dathang'));
    }
    public function update(Dathang $dathang){
        $status = request('status','1');
        if($dathang->status != 2){
            $dathang->update(['status' => $status]);
            return redirect()->route('dathang.index')->with('ok', 'Cập nhật thành công');
        }
        return redirect()->route('dathang.index')->with('no', 'Không thể cập nhật');
    }


  
}
