<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    public function products(){
       return $this->belongsToMany(Product::class)->withPivot(['price_at_selling_time','selling_price']);
    }
}
