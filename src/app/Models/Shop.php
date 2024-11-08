<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'region',
        'genre',
        'description',
        'image_url',
        'operation_patten',
        'seats_number',
    ];

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_shop', 'shop_id', 'admin_id');
    }
}
