<?php

namespace App\Console\Commands;

use Domain\Stock\Events\StockSplit;
use Domain\Stock\Stock;
use Domain\Support\Helpers\StringHelper;
use Illuminate\Console\Command;

class SplitStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:split';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers a split event';

    protected $stringHelper;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->stringHelper = new StringHelper();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stocksWithId = $this->choice('Choose the stock:', Stock::orderBy('name')->get()->map(function($stock) {
            return "$stock->name @$stock->id";
        })->toArray());
        $stock = Stock::byId($this->stringHelper->retrieveId($stocksWithId, "@"));

        $targetProportion = $this->ask('What is the target proportion?');
        $date = $this->ask("What is the date of the split? (Format: YYYY-MM-DD)");

        $this->info("$stock->name split from 1 to $targetProportion");

        if ($this->confirm('Do you wish to proceed?', true)) {
            event(new StockSplit($stock->id, $targetProportion, $date));
        }

        $this->info('Split event registered successfully!');
        return 0;
    }
}
