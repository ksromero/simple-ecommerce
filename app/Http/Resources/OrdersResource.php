<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class OrdersResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'sub_total' => $this->subtotal,
            'discount' => $this->discount,
            'total' => $this->total,
            'created_at' => Carbon::parse($this->created_at)->format('F d, Y h:i:s A'),
            'customer' => new UserResource($this->user),
            'items' => ProductsResource::collection($this->products)
        ];
    }

}
