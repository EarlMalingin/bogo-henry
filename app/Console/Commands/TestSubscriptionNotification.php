<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Session;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Notification;
use App\Services\SubscriptionNotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TestSubscriptionNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:subscription-notification {--create-test : Create a test monthly subscription that expires in 7 days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the monthly subscription expiration notification system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Monthly Subscription Expiration Notifications');
        $this->newLine();

        // Check if we should create a test subscription
        if ($this->option('create-test')) {
            $this->info('Creating test monthly subscription...');
            
            // Get first student and tutor
            $student = Student::first();
            $tutor = Tutor::where('registration_status', 'approved')->where('is_active', true)->first();
            
            if (!$student) {
                $this->error('❌ No student found. Please create a student account first.');
                return Command::FAILURE;
            }
            
            if (!$tutor) {
                $this->error('❌ No approved tutor found. Please create an approved tutor account first.');
                return Command::FAILURE;
            }
            
            // Create a test session that expires in 7 days
            // If today is Nov 16, 2025, and we want it to expire in 7 days (Nov 23),
            // then the start date should be Oct 23, 2025 (1 month before Nov 23)
            $endDate = Carbon::today()->addDays(7);
            $startDate = $endDate->copy()->subMonth();
            
            $this->info("Creating subscription:");
            $this->line("  Start Date: {$startDate->format('Y-m-d')}");
            $this->line("  End Date: {$endDate->format('Y-m-d')} (7 days from today)");
            $this->line("  Student: {$student->first_name} {$student->last_name}");
            $this->line("  Tutor: {$tutor->first_name} {$tutor->last_name}");
            
            // Check if test session already exists
            $existingSession = Session::where('student_id', $student->id)
                ->where('tutor_id', $tutor->id)
                ->where('booking_type', 'monthly')
                ->where('date', $startDate->format('Y-m-d'))
                ->where('status', 'accepted')
                ->first();
            
            if ($existingSession) {
                $this->warn("⚠️  Test session already exists (ID: {$existingSession->id})");
                $this->info("Using existing session for testing...");
            } else {
                $session = Session::create([
                    'student_id' => $student->id,
                    'tutor_id' => $tutor->id,
                    'session_type' => 'online',
                    'booking_type' => 'monthly',
                    'date' => $startDate->format('Y-m-d'),
                    'start_time' => '00:00:00',
                    'end_time' => '23:59:59',
                    'status' => 'accepted',
                    'rate' => $tutor->session_rate ?? 600.00,
                ]);
                
                $this->info("✅ Test session created (ID: {$session->id})");
            }
            
            $this->newLine();
        }
        
        // Show current monthly subscriptions
        $this->info('📋 Current Monthly Subscriptions:');
        $monthlySubscriptions = Session::where('status', 'accepted')
            ->where(function($q) {
                $q->where('booking_type', 'monthly')
                  ->orWhere(function($subQ) {
                      $subQ->where('start_time', '00:00:00')
                           ->where('end_time', '23:59:59');
                  });
            })
            ->with(['student', 'tutor'])
            ->get();
        
        if ($monthlySubscriptions->isEmpty()) {
            $this->warn('⚠️  No monthly subscriptions found.');
            $this->info('💡 Tip: Use --create-test flag to create a test subscription');
            return Command::SUCCESS;
        }
        
        $today = Carbon::today();
        $sevenDaysFromNow = Carbon::today()->addDays(7);
        
        $tableData = [];
        foreach ($monthlySubscriptions as $session) {
            $endDate = Carbon::parse($session->date)->copy()->addMonth();
            $daysRemaining = $today->diffInDays($endDate);
            $willExpire = $endDate->gte($today) && $endDate->lte($sevenDaysFromNow);
            
            $tableData[] = [
                'ID' => $session->id,
                'Student' => "{$session->student->first_name} {$session->student->last_name}",
                'Tutor' => "{$session->tutor->first_name} {$session->tutor->last_name}",
                'Start Date' => $session->date->format('Y-m-d'),
                'End Date' => $endDate->format('Y-m-d'),
                'Days Remaining' => $daysRemaining,
                'Will Expire?' => $willExpire ? '✅ Yes' : '❌ No',
            ];
        }
        
        $this->table(
            ['ID', 'Student', 'Tutor', 'Start Date', 'End Date', 'Days Remaining', 'Will Expire?'],
            $tableData
        );
        
        $this->newLine();
        
        // Run the notification check
        $this->info('🔔 Running notification check...');
        $this->info('📧 Email notifications will be sent via Gmail SMTP...');
        $this->newLine();
        
        $service = new SubscriptionNotificationService();
        $notificationsCreated = $service->checkExpiringSubscriptions();
        
        $this->info("✅ Created {$notificationsCreated} notification(s)");
        
        if ($notificationsCreated > 0) {
            $this->info("📧 Email notification(s) sent to student(s)");
        }
        
        // Show created notifications
        if ($notificationsCreated > 0) {
            $this->newLine();
            $this->info('📬 Created Notifications:');
            
            $notifications = Notification::where('type', 'subscription_expiring')
                ->whereDate('created_at', Carbon::today())
                ->orderBy('created_at', 'desc')
                ->limit($notificationsCreated)
                ->get();
            
            foreach ($notifications as $notification) {
                $this->line("  • {$notification->title}");
                $this->line("    {$notification->message}");
                $this->line("    Student ID: {$notification->user_id}");
                $this->newLine();
            }
        } else {
            $this->warn('⚠️  No notifications were created.');
            $this->info('💡 This could mean:');
            $this->line('   - No subscriptions expire within 7 days');
            $this->line('   - Notifications already exist for today');
        }
        
        $this->newLine();
        $this->info('✨ Test completed!');
        $this->info('💡 To view notifications, log in as a student and check the dashboard or notifications page.');
        
        return Command::SUCCESS;
    }
}
