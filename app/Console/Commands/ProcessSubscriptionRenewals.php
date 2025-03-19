<?php

namespace App\Console\Commands;

use App\Http\Controllers\SubscriptionController;
use Illuminate\Console\Command;

class ProcessSubscriptionRenewals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process automatic subscription renewals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting subscription renewal process...');
        
        $controller = app(SubscriptionController::class);
        $result = $controller->processAutoRenewals();
        
        $this->info('Subscription renewal process completed.');
        $this->info($result->getData()->message);
        
        return Command::SUCCESS;
    }
}
