<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use App\Models\Loaisp;
use App\Models\Sanpham;
use Illuminate\Http\Request;
use App\Models\Slide; 
use App\Models\Favorite;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy 3 slide đầu tiên
        $slide = Slide::orderBy('id', 'ASC')->take(3)->get();

        $deal_sanpham = Sanpham::where('id_loai', 4)->orderBy('created_at', 'DESC')->limit(6)->get();

        $new_sanpham = Sanpham::inRandomOrder()->limit(8)->get();
        return view('home.index', compact('slide','deal_sanpham','new_sanpham')); 
    }
    
    public function about()
    {
        return view('home.about');
    }
    public function blogs(Blogs $blogs)
    {
       
        $blog = Blogs::paginate(10);
        return view('home.blogs',compact('blog'));
    }
    public function chitiet(){
        return view('home.chitiet');
    }
     
    public function loaisp (Loaisp $cat){
        $sanpham = $cat->sanpham()->paginate(8);
        return view('home.loaisp',compact('cat','sanpham'));

    }
    public function sanpham (Sanpham $sanpham){
        
        $sanphams = Sanpham::where('id_loai',$sanpham->id_loai)->limit(12)->get();
        $randomsp = Sanpham::inRandomOrder()->limit(8)->get();
        return view('home.sanpham',compact('sanpham','sanphams','randomsp'));
    }
    public function favorite ( $sanpham_id){
        $data = [
            'sanpham_id' => $sanpham_id  ,
            'khachhang_id' => auth('cus')-> id()

        ];
        // dd($data);
        // dd(auth('cus')->id());
        $favorited = Favorite:: where(['sanpham_id'=> $sanpham_id , 'khachhang_id'=>auth('cus')->id()])->first();
        if( $favorited) {
            $favorited ->delete();
            return redirect()->back()-> with('ok','Bạn đã bỏ sản phẩm yêu thích thành công');

        }else{
            Favorite::create($data); 
            return redirect()->back()-> with('ok','Thêm sản phẩm yêu thích thành công');

        }
        
     
    }
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
       
        // Tìm kiếm sản phẩm dựa trên từ khóa 
        $query = Sanpham::query();
        
        if ($keyword) {
            $query->where('tensp', 'like', '%' . $keyword . '%');
        }
        $sanphams = $query->paginate(12);
        return view('home.search', compact('sanphams', 'keyword'));
    }
}