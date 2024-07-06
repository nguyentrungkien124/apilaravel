<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class khachhang extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'khachhang'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        
        'tenkh',
        'password',
        'email',
        'diachi',
        'sodt',
        'ghichu'
    ];

    public function carts(){
        return $this -> hasMany(Giohang::class,'khachhang_id','id');
    }

    public function dathangs(){
        return $this -> hasMany(Dathang::class,'khachhang_id','id')->orderBy('id','DESC');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function favorites() {
        return $this->hasMany(Favorite::class, 'khachhang_id', 'id');
    }
    
}
