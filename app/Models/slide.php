<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class slide extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'slide'; 
    
    protected $fillable = ['image','tenslide','gia'];

    public function sanpham() {
        return $this->hasMany(sanpham::class,'id_loai','id');
    }

    public function scopeSlide($q)
    {
        return $q->orderBy('id')->take(3)->get(); 
    }
    
    
}
