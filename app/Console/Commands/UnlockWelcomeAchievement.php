<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\Student;
use App\Models\Tutor;

class UnlockWelcomeAchievement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'achievements:unlock-welcome';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock the Welcome achievement for all existing users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Unlocking Welcome achievement for all users...');

        // Get the Welcome achievement
        $welcomeAchievement = Achievement::where('name', 'Welcome!')->first();

        if (!$welcomeAchievement) {
            $this->error('Welcome achievement not found. Please run the AchievementSeeder first.');
            return 1;
        }

        $unlockedCount = 0;

        // Unlock for all students
        $students = Student::all();
        foreach ($students as $student) {
            $userAchievement = UserAchievement::where('achievement_id', $welcomeAchievement->id)
                ->where('user_type', 'App\Models\Student')
                ->where('user_id', $student->id)
                ->first();

            if (!$userAchievement) {
                UserAchievement::create([
                    'achievement_id' => $welcomeAchievement->id,
                    'user_type' => 'App\Models\Student',
                    'user_id' => $student->id,
                    'progress' => 100,
                    'is_unlocked' => true,
                    'unlocked_at' => now(),
                ]);
                $unlockedCount++;
            }
        }

        // Unlock for all tutors
        $tutors = Tutor::all();
        foreach ($tutors as $tutor) {
            $userAchievement = UserAchievement::where('achievement_id', $welcomeAchievement->id)
                ->where('user_type', 'App\Models\Tutor')
                ->where('user_id', $tutor->id)
                ->first();

            if (!$userAchievement) {
                UserAchievement::create([
                    'achievement_id' => $welcomeAchievement->id,
                    'user_type' => 'App\Models\Tutor',
                    'user_id' => $tutor->id,
                    'progress' => 100,
                    'is_unlocked' => true,
                    'unlocked_at' => now(),
                ]);
                $unlockedCount++;
            }
        }

        $this->info("Successfully unlocked Welcome achievement for {$unlockedCount} users!");
        return 0;
    }
}

