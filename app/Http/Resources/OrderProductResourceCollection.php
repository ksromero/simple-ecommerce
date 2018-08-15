<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderProductResourceCollection extends ResourceCollection
{
    public function toArray($request)
<<<<<<< HEAD
    {    
        //return $this->kenn($request);
=======
    {
>>>>>>> filter
        return [
            'data' => $this->collection,
            'total' => round(($this->collection->sum('income')),2)
        ];
    }

    /*
    function kenn($request){
        $return = [];
        foreach($this->collection as $value){
            $return[] = [
                "id" => $value["id"],
                "description" => $value["description"]
            ];
        }

        return $return;
    }
    */


}
