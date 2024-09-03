<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
enum  Status: int
{
    case unpaid = 0;
    case paid = 1;
}

class Order extends Model
{
    use HasFactory;
    protected $casts = [
        'status'=>Status::class
    ];
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot(['quantity','price_at_selling_time','coupon_code_used']);;
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
