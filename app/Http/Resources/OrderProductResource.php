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
            'income' => $this->income, 
        ]; 
    }
}
