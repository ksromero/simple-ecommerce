<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderProductResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {    
        $total = 0;
        return [
            'data' => $this->collection,
            'total' => $this->collection->sum('income')
        ];
    }
}
