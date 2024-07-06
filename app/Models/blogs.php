<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    use HasFactory;
    protected $table='blogs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'content_title',
        'user_id',
        'hinhanh',
        'ghichu'
    ];
    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }


}
