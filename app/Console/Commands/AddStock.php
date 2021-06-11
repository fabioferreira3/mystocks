<?php

namespace App\Console\Commands;

use Domain\Stock\Services\StockAnalyzer;
use Domain\Stock\Stock;
use Illuminate\Console\Command;

class AddStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new stock';

    protected $analyzer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->analyzer = new StockAnalyzer();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stockCode = $this->ask('Type in the Stock code');
        $stock = Stock::byCode($stockCode);
        if ($stock) {
            $this->info("$stockCode already exists");
            return 0;
        }
        $stockAnalyse = $this->analyzer->symbolSearch($stockCode);

        if($stockAnalyse) {
           $this->info($stockAnalyse['name']);
           $this->info($stockAnalyse['type']);
           $this->info($stockAnalyse['alpha_symbol']);
           if ($this->confirm('Do you confirm these stock info?', true)){
                $stockType = $stockAnalyse['type'];
                $stockCompany = $stockAnalyse['name'];
                $stockSymbol = $stockAnalyse['alpha_symbol'];
           }
        } else {
            $this->info('Stock not found');
            $stockType = $this->choice(
                'Choose the Stock type',
                ['Equity','ETF', 'FII', 'BDR', 'UNT'],
                0
            );
            $stockCompany = $this->ask('Type in the Stock company');
            $stockSymbol = $this->ask('Type in the symbol');
        }

        $stockSector = $this->ask('Type in the Stock company sector');
        Stock::create([
            'name' => $stockCode,
            'type' => $stockType,
            'company' => $stockCompany,
            'symbol' => $stockSymbol,
            'sector' => $stockSector
        ]);
        $this->info($stockCode . ' created!');
        return 0;
    }
}
