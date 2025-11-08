<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\Session;
use App\Models\ActivitySubmission;
use App\Models\Review;

class StudentSettingsController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get all achievements for students
        $achievements = Achievement::where(function($query) {
            $query->where('type', 'student')
                  ->orWhere('type', 'both');
        })->where('is_active', true)->get();
        
        // Get user's achievements with progress
        $userAchievements = [];
        $totalPoints = 0;
        $unlockedCount = 0;
        
        foreach ($achievements as $achievement) {
            $userAchievement = UserAchievement::where('achievement_id', $achievement->id)
                ->where('user_type', 'App\Models\Student')
                ->where('user_id', $student->id)
                ->first();
            
            if (!$userAchievement) {
                $progress = $this->calculateProgress($student, $achievement);
                $isUnlocked = $progress >= 100;
                
                $userAchievement = UserAchievement::create([
                    'achievement_id' => $achievement->id,
                    'user_type' => 'App\Models\Student',
                    'user_id' => $student->id,
                    'progress' => $progress,
                    'is_unlocked' => $isUnlocked,
                    'unlocked_at' => $isUnlocked ? now() : null,
                ]);
            } else {
                // Update progress
                $progress = $this->calculateProgress($student, $achievement);
                $userAchievement->progress = $progress;
                
                // Check if achievement should be unlocked
                if (!$userAchievement->is_unlocked && $progress >= 100) {
                    $userAchievement->is_unlocked = true;
                    $userAchievement->unlocked_at = now();
                }
                $userAchievement->save();
            }
            
            if ($userAchievement->is_unlocked) {
                $totalPoints += $achievement->points;
                $unlockedCount++;
            }
            
            $userAchievements[] = [
                'achievement' => $achievement,
                'user_achievement' => $userAchievement,
            ];
        }
        
        // Calculate level based on total points
        $level = floor($totalPoints / 100) + 1;
        $pointsForNextLevel = ($level * 100) - $totalPoints;
        
        return view('student.achievements', compact('userAchievements', 'totalPoints', 'unlockedCount', 'level', 'pointsForNextLevel', 'student'));
    }
    
    private function calculateProgress($student, $achievement)
    {
        if (!$achievement->requirement_type || !$achievement->requirement_value) {
            return 0;
        }
        
        $current = 0;
        
        switch ($achievement->requirement_type) {
            case 'sessions_completed':
                $current = Session::where('student_id', $student->id)
                    ->where('status', 'completed')
                    ->count();
                break;
            case 'sessions_booked':
                $current = Session::where('student_id', $student->id)->count();
                break;
            case 'activities_submitted':
                $current = ActivitySubmission::where('student_id', $student->id)
                    ->where('status', 'submitted')
                    ->count();
                break;
            case 'perfect_ratings':
                // Count reviews with 5-star ratings given by students
                $current = Review::where('student_id', $student->id)
                    ->where('rating', 5)
                    ->count();
                break;
        }
        
        $progress = min(100, ($current / $achievement->requirement_value) * 100);
        return (int) $progress;
    }
}
