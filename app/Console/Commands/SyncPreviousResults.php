<?php

namespace App\Console\Commands;

use Domain\Stats\Jobs\SyncMonthlyPreviousResults;
use Domain\Stock\Stock;
use Illuminate\Console\Command;

class SyncPreviousResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:sync-previous-results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs monthly previous results';

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
        SyncMonthlyPreviousResults::dispatchSync();
        $this->info('Previous results synced successfully!');
        return 0;
    }
}
