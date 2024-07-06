<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giohang extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'giohang'; 
    protected $fillable = ['sanpham_id','khachhang_id','soluong','gia'];

    public function prod(){
        return $this->hasOne(Sanpham::class,'id','sanpham_id');
    }

   
    
}
