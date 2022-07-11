<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'order_id' => $this->id,
            'ordered products:' => json_decode($this->products),
            'total Price' => $this->totalPrice,
            'name' => $this->name,
            'address' => $this->address,
            'transactionID' => $this->transactionID,
        ];
    }
}
