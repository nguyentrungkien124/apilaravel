<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hoadonnhap extends Model
{
    use HasFactory;

    protected $table = 'hoadonnhap';
    protected $primaryKey = 'id';

    protected $fillable =[
        'maNhaPhanPhoi',
        'kieuthanhtoan',
        'maTaiKhoan',
        'tongtien'
    ];
    public function chitiets()
    {
        return $this->hasMany(Chitiethoadonnhap::class, 'maHoaDon','id');
    }
    
    
    public function matk()
    {
        return $this->belongsTo(Nhaphanphoi::class, 'maNhaPhanPhoi');
    }
}
