<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CleanDatabaseForDeployment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clean-for-deployment {--fresh : Drop all tables and re-run migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean database for deployment - removes test data and resets to production-ready state';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Cleaning database for deployment...');
        $this->newLine();

        if ($this->option('fresh')) {
            // Fresh migration - drops all tables and recreates
            $this->warn('⚠️  This will DROP ALL TABLES and recreate them!');
            if (!$this->confirm('Are you sure you want to continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }

            $this->info('Running fresh migrations...');
            Artisan::call('migrate:fresh', [], $this->getOutput());
            $this->newLine();
        } else {
            // Soft clean - just clear data from tables
            $this->info('Clearing data from tables (keeping structure)...');
            
            // Disable foreign key checks temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // List of tables to clear (in order to respect foreign keys)
            $tables = [
                'user_achievements',
                'user_streaks',
                'notifications',
                'activity_submissions',
                'activities',
                'assignment_answers',
                'assignments',
                'reviews',
                'answer_ratings',
                'problem_reports',
                'messages',
                'tutoring_sessions',
                'wallet_transactions',
                'wallets',
                'audit_logs',
            ];
            
            foreach ($tables as $table) {
                try {
                    $count = DB::table($table)->count();
                    DB::table($table)->truncate();
                    $this->info("  ✓ Cleared {$count} records from {$table}");
                } catch (\Exception $e) {
                    $this->warn("  ⚠ Could not clear {$table}: " . $e->getMessage());
                }
            }
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->newLine();
        }

        // Clear cache
        $this->info('Clearing application cache...');
        Artisan::call('cache:clear', [], $this->getOutput());
        Artisan::call('config:clear', [], $this->getOutput());
        Artisan::call('route:clear', [], $this->getOutput());
        Artisan::call('view:clear', [], $this->getOutput());
        $this->info('  ✓ Cache cleared');
        $this->newLine();

        // Seed essential data
        $this->info('Seeding essential data...');
        
        // Seed achievements
        Artisan::call('db:seed', ['--class' => 'AchievementSeeder'], $this->getOutput());
        $this->info('  ✓ Achievements seeded');
        
        $this->newLine();
        $this->info('✅ Database cleaning completed!');
        $this->info('Your database is now ready for deployment.');
        
        return 0;
    }
}

