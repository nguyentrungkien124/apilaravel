<?php

namespace App\Models;

use App\Mail\OrderMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Dathang extends Model
{
    use  HasFactory;
    protected $appends = ['totalPrice'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'dathang'; 
    protected $primaryKey = 'id';
    // protected $appends = ['totalPrice'];
  
    protected $fillable = [
        
        'tenkh',
        'email',
        'diachi',
        'sodt',
        'status',
        'khachhang_id',
        'token'
    ];
    public function khachhang(){
        return $this-> hasOne(khachhang::class, 'id','khachhang_id');
    }
    public function details(){
        return $this-> hasMany(Chitietdathang::class, 'dathang_id','id');
    }
    
    public function getTotalPriceAttribute(){
      $t = 0;
      foreach ($this->details as $tongtien){
        $t += $tongtien->gia*$tongtien->soluong;
      }
      return $t;
    }
   
}
