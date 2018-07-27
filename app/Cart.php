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
                }
            }
            if(!$qty == null){
                $storedItem['qty'] += $qty;
                $this->totalQty += $qty;
                $storedItem['price'] = $item->price * $storedItem['qty'];
                $price = $item->price * $qty;
                $this->totalPrice += $price;
            }else{
                $storedItem['qty']++;
                $this->totalQty++;
                $storedItem['price'] = $item->price * $storedItem['qty'];
                $this->totalPrice += $item->price;
            }
            $this->items[$id] = $storedItem;
    }

    public function reduceByOne($id){
        $this->items[$id]['qty']--;
        $this->items[$id]['price'] -= $this->items[$id]['item']['price'];
        $this->totalQty--;
        $this->totalPrice -= $this->items[$id]['item']['price'];
        if($this->items[$id]['qty'] <= 0){
            unset($this->items[$id]);
        }
    }
    public function reduceAll($id){
        $this->totalQty -= $this->items[$id]['qty'];
        $this->totalPrice -= $this->items[$id]['price'];
        $this->items[$id]['qty'] = 0;
        $this->items[$id]['price'] = 0;
        if($this->items[$id]['qty'] <= 0){
            unset($this->items[$id]);
        }
    }
    public function updateCart($id, $qty){
        $totalQty = 0;
        $totalPrice = 0;
        $this->items[$id]['qty'] = $qty;
        $this->items[$id]['price'] = $qty * $this->items[$id]['item']['price'];
        foreach($this->items as $item){
            $totalQty += $item['qty'];
            $totalPrice += $item['price'];
        }
        $this->totalQty = $totalQty;
        $this->totalPrice = $totalPrice;
        if($this->items[$id]['qty'] <= 0){
            unset($this->items[$id]);
        }
    }
}
