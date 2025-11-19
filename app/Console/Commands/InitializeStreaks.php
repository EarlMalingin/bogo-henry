<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Tutor;
use App\Services\StreakService;

class InitializeStreaks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streaks:initialize {--user-id=} {--type=student}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize streaks for users (useful for testing or existing users)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $streakService = new StreakService();
        
        if ($this->option('user-id')) {
            // Initialize for specific user
            $userId = $this->option('user-id');
            $type = $this->option('type');
            
            if ($type === 'student') {
                $user = Student::find($userId);
                if (!$user) {
                    $this->error("Student with ID {$userId} not found.");
                    return 1;
                }
                
                $loginStreak = $streakService->checkLoginStreak($user, 'student');
                $this->info("Initialized login streak for student {$userId}: {$loginStreak} days");
            } else {
                $user = Tutor::find($userId);
                if (!$user) {
                    $this->error("Tutor with ID {$userId} not found.");
                    return 1;
                }
                
                $loginStreak = $streakService->checkLoginStreak($user, 'tutor');
                $this->info("Initialized login streak for tutor {$userId}: {$loginStreak} days");
            }
        } else {
            // Initialize for all users
            $this->info('Initializing streaks for all users...');
            
            $students = Student::all();
            foreach ($students as $student) {
                $streakService->checkLoginStreak($student, 'student');
            }
            $this->info("Initialized login streaks for {$students->count()} students");
            
            $tutors = Tutor::all();
            foreach ($tutors as $tutor) {
                $streakService->checkLoginStreak($tutor, 'tutor');
            }
            $this->info("Initialized login streaks for {$tutors->count()} tutors");
        }
        
        $this->info('Done!');
        return 0;
    }
}

