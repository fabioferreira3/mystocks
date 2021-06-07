<?php

namespace App\Console\Commands;

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
        $stockCode = $this->ask('Type in the Stock code');
        $stockType = $this->choice(
            'Choose the Stock type',
            ['PN', 'ON', 'ETF', 'FII', 'BDR', 'UNT'],
            0
        );
        $stockCompany = $this->ask('Type in the Stock company');
        $stockSector = $this->ask('Type in the Stock company sector');
        Stock::create([
            'name' => $stockCode,
            'type' => $stockType,
            'company' => $stockCompany,
            'sector' => $stockSector
        ]);
        $this->info($stockCode . ' created!');
        return 0;
    }
}
