<?php

namespace Domain\Stock\Services;

use AlphaVantage;

class StockAnalyzer {

    public $client;

    public function __construct()
    {
        $options = new AlphaVantage\Options();
        $options->setApiKey(config('app.alphavantage_key'));
        $this->client = new AlphaVantage\Client($options);
    }

    public function symbolSearch(string $symbol) {
        $data = $this->client->timeSeries()->symbolSearch($symbol);
        if (isset($data['bestMatches']) && $data['bestMatches'] > 0) {
            $results = $data['bestMatches'][0];
            return [
                'symbol' => $symbol,
                'alpha_symbol' => $results['1. symbol'],
                'name' => $results['2. name'],
                'type' => $results['3. type']
            ];
        };
        return null;
    }

    public function quote(string $symbol) {
        $data = $this->client->timeSeries()->globalQuote($symbol);
        if (isset($data['Global Quote'])) {
            $results = $data['Global Quote'];
            return [
                'symbol' => $symbol,
                'alpha_symbol' => $results['01. symbol'],
                'open' => $results['02. open'],
                'high' => $results['03. high'],
                'low' => $results['04. low'],
                'price' => $results['05. price'],
                'change' => $results['09. change'],
                'change_percent' => $results['10. change percent']
            ];
        };
        return null;
    }
}
