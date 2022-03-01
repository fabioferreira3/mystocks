<?php

namespace App\Console\Commands;

use Domain\Broker\BrokerageNote;
use Domain\Stats\MonthlyResult;
use Domain\Stock\StockPosition;
use Domain\Stock\StockTransaction;
use Illuminate\Console\Command;

class ProcessStockTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:re-process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-process all stock transactions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->confirm('All stock positions and transaction records will be removed. Are you sure?', true)) {
            StockPosition::truncate();
            StockTransaction::truncate();
            BrokerageNote::truncate();
            $this->call('event-sourcing:replay', ['projector' => ['Domain\\Stock\\Projectors\\StockTransactionProjector']]);
            $this->call('event-sourcing:replay', ['projector' => ['Domain\\Stock\\Projectors\\StockPositionProjector']]);
            $this->info('Stock transactions re-processed successfully!');
        };

        return 0;
    }
}
