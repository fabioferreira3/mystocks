<?php

namespace Domain\Stock\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->collection,
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page_url' => $this->url($this->lastPage()),
            'next_page_url' => $this->nextPageUrl(),
            "per_page" => $this->perPage(),
            "total" => $this->total(),
            "last_page" => $this->lastPage()
        ];
    }
}
