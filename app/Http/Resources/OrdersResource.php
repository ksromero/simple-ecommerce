<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\ProductsResource;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sub_total= 0;
        $quantity = 0;

        foreach($this->products as $product){
        $sub_total += ($product->pivot->quantity * $product->price);
        $quantity += $product->pivot->quantity;
        }

        $discount = round((10 / 100) * $sub_total, 2);
        $total = $sub_total - $discount;
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'address' => $this->address,
            'sub_total' => $sub_total,
            'discount' => $discount,
            'total_price' => $total,
            'created_at' => Carbon::parse($this->created_at)->format('F d, Y h:i:s A'),
            'customer' => $this->user,
            'items' => ProductsResource::collection($this->products),
        ];
    }
}
