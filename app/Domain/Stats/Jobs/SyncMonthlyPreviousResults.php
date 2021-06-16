<?php

namespace Domain\Stats\Jobs;

use Domain\Stats\Repositories\StatsRepositories;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SyncMonthlyPreviousResults
{
    use Dispatchable, SerializesModels;

    private $statsRepository;
    private $year;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($year = null)
    {
        $this->statsRepository = new StatsRepositories();
        $this->year = $year;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $monthlyResults = $this->statsRepository->getResultsByYear($this->year);
        $monthlyResults->each(function($monthResult) {
            $monthResult->setPreviousResults();
        });
    }
}
