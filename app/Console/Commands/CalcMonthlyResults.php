<?php

namespace App\Console\Commands;

use Domain\Stats\MonthlyResult;
use Illuminate\Console\Command;

class CalcMonthlyResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:calc-monthly-results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcs monthly results';

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
        if ($this->confirm('All monthly results records will be removed. Are you sure?', true)) {
            MonthlyResult::truncate();
            $this->call('event-sourcing:replay', ['projector' => ['Domain\\Stats\\Projectors\\MonthlyResultsProjector']]);
            $this->info('Month results generated successfully!');
        };

        return 0;
    }
}
