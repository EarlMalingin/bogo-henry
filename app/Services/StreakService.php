<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Tutor;
use App\Models\ActivitySubmission;
use App\Models\Session;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class StreakService
{
    /**
     * Update or create streak for a user
     */
    public function updateStreak($user, $userType, $streakType)
    {
        $userModelClass = $userType === 'student' ? 'App\Models\Student' : 'App\Models\Tutor';
        
        $streak = DB::table('user_streaks')
            ->where('user_id', $user->id)
            ->where('user_type', $userModelClass)
            ->where('streak_type', $streakType)
            ->first();

        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        if (!$streak) {
            // Create new streak
            DB::table('user_streaks')->insert([
                'user_id' => $user->id,
                'user_type' => $userModelClass,
                'streak_type' => $streakType,
                'current_count' => 1,
                'longest_count' => 1,
                'last_activity_date' => $today,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->checkStreakMilestones($user, $userType, $streakType, 1);
            return 1;
        }

        $lastActivity = \Carbon\Carbon::parse($streak->last_activity_date)->startOfDay();
        
        // Check if streak should continue or reset
        if ($lastActivity->equalTo($today)) {
            // Already counted today
            return $streak->current_count;
        } elseif ($lastActivity->equalTo($yesterday)) {
            // Continue streak
            $newCount = $streak->current_count + 1;
            $longestCount = max($streak->longest_count, $newCount);
            
            DB::table('user_streaks')
                ->where('id', $streak->id)
                ->update([
                    'current_count' => $newCount,
                    'longest_count' => $longestCount,
                    'last_activity_date' => $today,
                    'updated_at' => now(),
                ]);
            
            $this->checkStreakMilestones($user, $userType, $streakType, $newCount);
            return $newCount;
        } else {
            // Streak broken, reset
            DB::table('user_streaks')
                ->where('id', $streak->id)
                ->update([
                    'current_count' => 1,
                    'last_activity_date' => $today,
                    'updated_at' => now(),
                ]);
            
            return 1;
        }
    }

    /**
     * Check and reward streak milestones
     */
    private function checkStreakMilestones($user, $userType, $streakType, $count)
    {
        $milestones = [3, 7, 14, 30, 60, 100];
        
        foreach ($milestones as $milestone) {
            if ($count === $milestone) {
                $this->rewardStreakMilestone($user, $userType, $streakType, $milestone);
            }
        }
    }

    /**
     * Reward streak milestone
     */
    private function rewardStreakMilestone($user, $userType, $streakType, $days)
    {
        $rewards = [
            3 => ['points' => 10, 'message' => '3-day streak! Keep it up! 🔥'],
            7 => ['points' => 50, 'message' => 'Amazing! 7-day streak! 🎉'],
            14 => ['points' => 100, 'message' => 'Incredible! 2-week streak! ⭐'],
            30 => ['points' => 200, 'message' => 'Outstanding! 30-day streak! 🌟'],
            60 => ['points' => 500, 'message' => 'Legendary! 60-day streak! 👑'],
            100 => ['points' => 1000, 'message' => 'Unstoppable! 100-day streak! 🏆'],
        ];

        if (isset($rewards[$days])) {
            $reward = $rewards[$days];
            
            // Award points (you'll need to implement a points system)
            // $this->awardPoints($user, $userType, $reward['points']);
            
            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'user_type' => $userType,
                'type' => 'streak_milestone',
                'title' => "{$days}-Day Streak Achieved!",
                'message' => $reward['message'] . " You earned {$reward['points']} bonus points!",
            ]);
        }
    }

    /**
     * Get current streak for a user
     */
    public function getCurrentStreak($user, $userType, $streakType)
    {
        $userModelClass = $userType === 'student' ? 'App\Models\Student' : 'App\Models\Tutor';
        
        $streak = DB::table('user_streaks')
            ->where('user_id', $user->id)
            ->where('user_type', $userModelClass)
            ->where('streak_type', $streakType)
            ->first();

        return $streak ? $streak->current_count : 0;
    }

    /**
     * Get longest streak for a user
     */
    public function getLongestStreak($user, $userType, $streakType)
    {
        $userModelClass = $userType === 'student' ? 'App\Models\Student' : 'App\Models\Tutor';
        
        $streak = DB::table('user_streaks')
            ->where('user_id', $user->id)
            ->where('user_type', $userModelClass)
            ->where('streak_type', $streakType)
            ->first();

        return $streak ? $streak->longest_count : 0;
    }

    /**
     * Get all streaks for a user
     */
    public function getAllStreaks($user, $userType)
    {
        $userModelClass = $userType === 'student' ? 'App\Models\Student' : 'App\Models\Tutor';
        
        return DB::table('user_streaks')
            ->where('user_id', $user->id)
            ->where('user_type', $userModelClass)
            ->get()
            ->keyBy('streak_type');
    }

    /**
     * Check if user logged in today (for login streak)
     */
    public function checkLoginStreak($user, $userType)
    {
        // This would be called when user logs in
        return $this->updateStreak($user, $userType, 'daily_login');
    }
    
    /**
     * Initialize streak for a user (useful for existing users)
     */
    public function initializeStreak($user, $userType, $streakType)
    {
        return $this->updateStreak($user, $userType, $streakType);
    }

    /**
     * Check activity submission streak
     */
    public function checkActivitySubmissionStreak($user, $userType)
    {
        return $this->updateStreak($user, $userType, 'activity_submission');
    }

    /**
     * Check activity creation streak (for tutors)
     */
    public function checkActivityCreationStreak($user, $userType)
    {
        return $this->updateStreak($user, $userType, 'activity_created');
    }

    /**
     * Check perfect score streak
     */
    public function checkPerfectScoreStreak($user, $userType, $score, $totalPoints)
    {
        if ($score === $totalPoints) {
            return $this->updateStreak($user, $userType, 'perfect_score');
        } else {
            // Reset perfect score streak if not perfect
            $this->resetStreak($user, $userType, 'perfect_score');
            return 0;
        }
    }

    /**
     * Reset a streak
     */
    public function resetStreak($user, $userType, $streakType)
    {
        $userModelClass = $userType === 'student' ? 'App\Models\Student' : 'App\Models\Tutor';
        
        DB::table('user_streaks')
            ->where('user_id', $user->id)
            ->where('user_type', $userModelClass)
            ->where('streak_type', $streakType)
            ->update([
                'current_count' => 0,
                'updated_at' => now(),
            ]);
    }
}

