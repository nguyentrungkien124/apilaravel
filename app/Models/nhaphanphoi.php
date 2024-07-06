<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nhaphanphoi extends Model
{
    use HasFactory;
    protected $table = 'nhaphanphoi';
    protected $primaryKey = 'MaNPP';
    public $timestamps = false;

    protected $fillable = ['TenNPP','DiaChi','Email','SoDienThoai'];

}
