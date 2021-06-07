<?php

namespace Domain\Stock\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'stock' => $this->stock->name,
            'type' => $this->type,
            'amount' => $this->amount,
            'unit_price' => $this->unit_price,
            'total_price' => (float) number_format($this->unit_price * $this->amount, 2),
            'date' => $this->date->format('Y-m-d')
        ];
    }
}
