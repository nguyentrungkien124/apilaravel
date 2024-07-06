<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanpham extends Model
{
    use HasFactory;
    protected $appends = ['favorited','total_sold'];
    protected $table = 'sanpham'; // Đặt tên bảng tại đây

    protected $fillable = ['tensp','id_loai','tenhang','gia','giakm','hinhanh','soluong','tinhtrang','mota'];

    public function cat() {
        return $this->hasOne(Loaisp::class,'id','id_loai');

    }
    public function getFavoritedAttribute(){
        $favorited = Favorite:: where(['sanpham_id'=> $this-> id , 'khachhang_id'=>auth('cus')->id()])->first();
        return  $favorited ? true : false;
    }
    // xem số lượng đã bán láy từ chi tiết đặt hàng
    public function getTotalSoldAttribute()
    {
        return $this->chitietdathangs()->sum('soluong');
    }
    public function chitietdathangs()
    {
        return $this->hasMany(Chitietdathang::class, 'sanpham_id');
    }
}