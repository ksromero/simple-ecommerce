<?php

namespace App;

class Cart
{
    public $items = null;
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct($oldCart){
        if($oldCart){
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
        }
    }
    public function add($item, $id, $qty = null){
            $storedItem = ['qty' => 0, 'price' => $item->price, 'item' => $item];
            //oldcart
            if($this->items){
                if(array_key_exists($id, $this->items)){
                    $storedItem = $this->items[$id];
                    $holder = $storedItem['price'];
                }
            }
            if(!$qty == null){
                $storedItem['qty'] += $qty;
                $this->totalQty += $qty;
                $storedItem['price'] = $item->price * $storedItem['qty'];
                if(isset($holder)){
                    $this->totalPrice += $holder;
                }else{
                    $this->totalPrice += $storedItem['price'];
                }          
            }else{
                $storedItem['qty']++;
                $this->totalQty++;
                $storedItem['price'] = $item->price * $storedItem['qty'];
                $this->totalPrice += $item->price;
            }
            $this->items[$id] = $storedItem;
    }
}
