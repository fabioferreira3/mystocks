<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-processes all events';

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
        if ($this->confirm('Are you sure?', true)) {
            $this->call('transactions:re-process');
            $this->call('stats:refresh-monthly-results');
        }

        $this->info('All events and projectors reprocessed successfully');
        return 0;
    }
}
