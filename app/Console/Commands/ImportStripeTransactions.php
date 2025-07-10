<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SalesReportController;

class ImportStripeTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:stripe-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Stripe transactions into sales_report table';

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
        app(SalesReportController::class)->importStripeTransactions();
        $this->info('Stripe transactions imported successfully.');
    }
}
