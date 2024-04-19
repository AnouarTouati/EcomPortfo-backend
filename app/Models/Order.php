<?php

namespace App\Models;

use App\Http\Controllers\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $casts = [
        'status'=>Status::class
    ];
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot(['quantity','price_at_selling_time','selling_price','coupon_code_used']);;
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
