<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    public function toArray($request) 
    {
        return [ 
            'id' => $this->id, 
            'name' => $this->name, 
            'price' => $this->price, 
            'description' => $this->description,
            'quantity' => $this->quantity,
            'orders' => $this->except($this->orders),
            'income' => $this->income, 
        ]; 
    }
    public function except($req){
        $arr = [];
        foreach($req as $value){
            $arr[] = [
                'id' => $value['id'],
                'user_id' => $value['user_id'],
                'address' => $value['address'],
                'pivot' => $value->pivot
            ];
        }
        return $arr;
    }
}
