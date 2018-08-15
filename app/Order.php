<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'product_id', 'user_id', 'quantity', 'address' 
    ];
    public function getSubTotalAttribute() { 
        $sub_total= 0;
        foreach($this->products as $product){
            $sub_total += ($product->pivot->quantity * $product->price);
        }
        return round($sub_total,2);
    }
    public function getDiscountAttribute() { 
        return round((10 / 100) * $this->subtotal, 2);
    }
    public function getTotalAttribute(){
        return round(($this->subtotal - $this->discount), 2);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    }
}
