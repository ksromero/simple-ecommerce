<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class ProductsResource extends JsonResource
{
    private $quantity;
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
            'name' => $this->name, 
            'price' => $this->price, 
            'description' => $this->description,
            'cover_image' => $this->cover_image,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d h:m:s')
        ]; 
    }
}
