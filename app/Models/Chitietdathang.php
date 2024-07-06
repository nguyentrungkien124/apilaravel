<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chitietdathang extends Model
{
    use  HasFactory;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'chitietdathang'; 


    protected $fillable = [
        
        'sanpham_id',
        'dathang_id',
        'soluong',
        'gia'
    ];
    
    public function sanpham(){
        return $this -> hasOne(Sanpham::class,'id','sanpham_id');
    }
    
}
