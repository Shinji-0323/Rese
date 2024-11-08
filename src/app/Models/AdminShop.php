<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminShop extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'shop_id',
    ];

    protected $table = 'admin_shop';

    protected $fillable = ['admin_id', 'shop_id'];
}
