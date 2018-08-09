<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
        return [
            'id' => $this->id,
            'address' => $this->address,
            'sub_total' => $this->subtotal,
            'discount' => $this->discount,
            'total_price' => $this->total,
            'created_at' => Carbon::parse($this->created_at)->format('F d, Y h:i:s A'),
            'customer' => $this->user,
            'items' => ProductsResource::collection($this->products)
        ];
    }
}
