<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    public function getQuantityAttribute(){ 
        $quantity = 0; 
        foreach($this->orders as $order){ 
            $quantity += $order->pivot->quantity; 
        } 
        return $quantity; 
    } 
    public function getIncomeAttribute(){ 
        $sub_total = $this->price * $this->quantity; 
        $discount = round((10 / 100) * $sub_total, 2); 
        return round(($sub_total - $discount), 2); 
    }
    public function orders(){
        return $this->belongsToMany(Order::class)->withPivot('quantity')->withTimestamps();
    }
}