<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['stripeId'];
    protected $hidden = ['updated_at'];

    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }
}
