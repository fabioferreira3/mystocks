<?php

namespace App\Console\Commands;

use Domain\Stock\Events\StockInplit;
use Domain\Stock\Stock;
use Support\Helpers\StringHelper;
use Illuminate\Console\Command;

class InplitStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:inplit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registers an inplit event';

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

        $this->info("$stock->name inplit from $targetProportion to 1");

        if ($this->confirm('Do you wish to proceed?', true)) {
            event(new StockInplit($stock->id, $targetProportion, $date));
        }

        $this->info('Inplit event registered successfully!');
        return 0;
    }
}
