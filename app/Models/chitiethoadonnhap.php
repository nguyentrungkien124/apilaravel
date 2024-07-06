<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chitiethoadonnhap extends Model
{
    use HasFactory;

    protected $table = 'chitiethoadonnhap';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'maHoaDon',
        'sanpham_id',
        'soLuong',
        'giaNhap',

    ];
    public function hoadonnhap()
    {
        return $this->belongsTo(Hoadonnhap::class, 'maHoaDon','id');
    }

    public function sanpham()
    {
        return $this->belongsTo(Sanpham::class, 'sanpham_id','id');
    }
}
