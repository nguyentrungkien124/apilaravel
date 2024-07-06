<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $table = 'yeuthich'; 
    protected $primaryKey = 'id';

    protected $fillable = ['sanpham_id','khachhang_id'];

    public function prod(){
        return $this->hasOne(Sanpham::class, 'id','sanpham_id');
    }
}
