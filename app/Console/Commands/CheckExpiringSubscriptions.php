<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SubscriptionNotificationService;

class CheckExpiringSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for monthly subscriptions expiring in 7 days and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring monthly subscriptions...');

        $service = new SubscriptionNotificationService();
        $notificationsCreated = $service->checkExpiringSubscriptions();

        $this->info("✅ Created {$notificationsCreated} notification(s) for expiring subscriptions.");
        return Command::SUCCESS;
    }
}
