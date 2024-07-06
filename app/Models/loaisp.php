<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loaisp extends Model
{
    use HasFactory;

    protected $table = 'loaisp';
    protected $fillable = ['tenlsp', 'trangthai'];
    // 1-n
    public function sanpham()
    {
        return $this->hasMany(Sanpham::class, 'id_loai', 'id')->orderBy('created_at','DESC');
    }
   
}
