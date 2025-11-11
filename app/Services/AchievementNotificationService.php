<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\Notification;

class AchievementNotificationService
{
    /**
     * Check and notify about achievement progress for a user
     * 
     * @param mixed $user The user (Student or Tutor model)
     * @param string $userType 'student' or 'tutor'
     * @param string $triggerType The type of action that triggered this check (e.g., 'sessions_completed', 'activities_submitted')
     */
    public function checkAndNotifyProgress($user, $userType, $triggerType = null)
    {
        $userModelClass = $userType === 'student' ? 'App\Models\Student' : 'App\Models\Tutor';
        
        // Get all achievements for this user type
        $achievements = Achievement::where(function($query) use ($userType) {
            $query->where('type', $userType)
                  ->orWhere('type', 'both');
        })->where('is_active', true)->get();
        
        foreach ($achievements as $achievement) {
            // Skip if trigger type doesn't match
            if ($triggerType && $achievement->requirement_type !== $triggerType) {
                continue;
            }
            
            // Get or create user achievement
            $userAchievement = UserAchievement::where('achievement_id', $achievement->id)
                ->where('user_type', $userModelClass)
                ->where('user_id', $user->id)
                ->first();
            
            if (!$userAchievement) {
                $progress = $this->calculateProgress($user, $achievement, $userType);
                $isUnlocked = $progress >= 100;
                
                $userAchievement = UserAchievement::create([
                    'achievement_id' => $achievement->id,
                    'user_type' => $userModelClass,
                    'user_id' => $user->id,
                    'progress' => $progress,
                    'is_unlocked' => $isUnlocked,
                    'unlocked_at' => $isUnlocked ? now() : null,
                ]);
                
                // Notify if unlocked immediately
                if ($isUnlocked) {
                    $this->createUnlockNotification($user, $userType, $achievement);
                } elseif ($progress > 0) {
                    // Notify about initial progress
                    $this->createProgressNotification($user, $userType, $achievement, $progress);
                }
            } else {
                // Update progress
                $oldProgress = $userAchievement->progress;
                $wasUnlocked = $userAchievement->is_unlocked;
                
                $progress = $this->calculateProgress($user, $achievement, $userType);
                $userAchievement->progress = $progress;
                
                // Check if achievement should be unlocked
                if (!$userAchievement->is_unlocked && $progress >= 100) {
                    $userAchievement->is_unlocked = true;
                    $userAchievement->unlocked_at = now();
                    $userAchievement->save();
                    
                    // Notify about unlock
                    $this->createUnlockNotification($user, $userType, $achievement);
                } else {
                    $userAchievement->save();
                    
                    // Notify about progress if it increased and reached a milestone
                    if ($progress > $oldProgress) {
                        // Check if we've reached a milestone (25%, 50%, 75%)
                        $milestones = [25, 50, 75];
                        $reachedMilestone = false;
                        foreach ($milestones as $milestone) {
                            if ($oldProgress < $milestone && $progress >= $milestone) {
                                $this->createProgressNotification($user, $userType, $achievement, $progress);
                                $reachedMilestone = true;
                                break;
                            }
                        }
                        // Also notify if progress increased by at least 10% (but not on milestones already handled)
                        if (!$reachedMilestone && ($progress - $oldProgress) >= 10) {
                            $this->createProgressNotification($user, $userType, $achievement, $progress);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Calculate progress for an achievement
     */
    private function calculateProgress($user, $achievement, $userType)
    {
        if (!$achievement->requirement_type || !$achievement->requirement_value) {
            return 0;
        }
        
        $current = 0;
        
        switch ($achievement->requirement_type) {
            case 'sessions_completed':
                if ($userType === 'student') {
                    $current = \App\Models\Session::where('student_id', $user->id)
                        ->where('status', 'completed')
                        ->count();
                } else {
                    $current = \App\Models\Session::where('tutor_id', $user->id)
                        ->where('status', 'completed')
                        ->count();
                }
                break;
            case 'sessions_booked':
                $current = \App\Models\Session::where('student_id', $user->id)->count();
                break;
            case 'activities_submitted':
                $current = \App\Models\ActivitySubmission::where('student_id', $user->id)
                    ->where('status', 'submitted')
                    ->count();
                break;
            case 'activities_created':
                $current = \App\Models\Activity::where('tutor_id', $user->id)->count();
                break;
            case 'perfect_ratings':
                if ($userType === 'student') {
                    $current = \App\Models\Review::where('student_id', $user->id)
                        ->where('rating', 5)
                        ->count();
                } else {
                    $current = \App\Models\Review::where('tutor_id', $user->id)
                        ->where('rating', 5)
                        ->count();
                }
                break;
            case 'students_taught':
                $current = \App\Models\Session::where('tutor_id', $user->id)
                    ->distinct('student_id')
                    ->count('student_id');
                break;
        }
        
        $progress = min(100, ($current / $achievement->requirement_value) * 100);
        return (int) $progress;
    }
    
    /**
     * Create notification when achievement is unlocked
     */
    private function createUnlockNotification($user, $userType, $achievement)
    {
        Notification::create([
            'user_id' => $user->id,
            'user_type' => $userType,
            'type' => 'achievement_unlocked',
            'title' => 'Achievement Unlocked! 🎉',
            'message' => "Congratulations! You've unlocked the '{$achievement->name}' achievement and earned {$achievement->points} points!",
        ]);
    }
    
    /**
     * Create notification when progress is made
     */
    private function createProgressNotification($user, $userType, $achievement, $progress)
    {
        Notification::create([
            'user_id' => $user->id,
            'user_type' => $userType,
            'type' => 'achievement_progress',
            'title' => 'Achievement Progress! 📈',
            'message' => "You're {$progress}% complete on '{$achievement->name}'! Keep it up!",
        ]);
    }
}

