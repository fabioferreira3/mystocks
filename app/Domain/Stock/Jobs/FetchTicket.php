<?php

namespace Domain\Stock\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use AlphaVantage;

class FetchTicket {

    use Dispatchable, SerializesModels;

    protected $client;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new AlphaVantage\Options();
    }
}
