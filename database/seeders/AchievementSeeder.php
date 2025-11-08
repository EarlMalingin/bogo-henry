<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            // Student Achievements
            [
                'name' => 'First Steps',
                'description' => 'Book your first tutoring session',
                'icon' => 'fas fa-star',
                'badge_color' => '#4a90e2',
                'type' => 'student',
                'category' => 'sessions',
                'points' => 10,
                'requirement_value' => 1,
                'requirement_type' => 'sessions_booked',
                'is_active' => true,
            ],
            [
                'name' => 'Dedicated Learner',
                'description' => 'Complete 5 tutoring sessions',
                'icon' => 'fas fa-graduation-cap',
                'badge_color' => '#28a745',
                'type' => 'student',
                'category' => 'sessions',
                'points' => 50,
                'requirement_value' => 5,
                'requirement_type' => 'sessions_completed',
                'is_active' => true,
            ],
            [
                'name' => 'Scholar',
                'description' => 'Complete 10 tutoring sessions',
                'icon' => 'fas fa-book',
                'badge_color' => '#ffc107',
                'type' => 'student',
                'category' => 'sessions',
                'points' => 100,
                'requirement_value' => 10,
                'requirement_type' => 'sessions_completed',
                'is_active' => true,
            ],
            [
                'name' => 'Active Student',
                'description' => 'Submit 5 activity assignments',
                'icon' => 'fas fa-tasks',
                'badge_color' => '#17a2b8',
                'type' => 'student',
                'category' => 'activities',
                'points' => 75,
                'requirement_value' => 5,
                'requirement_type' => 'activities_submitted',
                'is_active' => true,
            ],
            [
                'name' => 'Perfect Score',
                'description' => 'Give 5 perfect 5-star ratings',
                'icon' => 'fas fa-star',
                'badge_color' => '#ffd700',
                'type' => 'student',
                'category' => 'social',
                'points' => 50,
                'requirement_value' => 5,
                'requirement_type' => 'perfect_ratings',
                'is_active' => true,
            ],
            
            // Tutor Achievements
            [
                'name' => 'First Session',
                'description' => 'Accept your first tutoring session',
                'icon' => 'fas fa-chalkboard-teacher',
                'badge_color' => '#4a90e2',
                'type' => 'tutor',
                'category' => 'sessions',
                'points' => 10,
                'requirement_value' => 1,
                'requirement_type' => 'sessions_accepted',
                'is_active' => true,
            ],
            [
                'name' => 'Experienced Tutor',
                'description' => 'Complete 10 tutoring sessions',
                'icon' => 'fas fa-user-graduate',
                'badge_color' => '#28a745',
                'type' => 'tutor',
                'category' => 'sessions',
                'points' => 100,
                'requirement_value' => 10,
                'requirement_type' => 'sessions_completed',
                'is_active' => true,
            ],
            [
                'name' => 'Master Teacher',
                'description' => 'Complete 25 tutoring sessions',
                'icon' => 'fas fa-crown',
                'badge_color' => '#ffc107',
                'type' => 'tutor',
                'category' => 'sessions',
                'points' => 250,
                'requirement_value' => 25,
                'requirement_type' => 'sessions_completed',
                'is_active' => true,
            ],
            [
                'name' => 'Activity Creator',
                'description' => 'Create 10 activities for students',
                'icon' => 'fas fa-file-alt',
                'badge_color' => '#17a2b8',
                'type' => 'tutor',
                'category' => 'activities',
                'points' => 75,
                'requirement_value' => 10,
                'requirement_type' => 'activities_created',
                'is_active' => true,
            ],
            [
                'name' => 'Student Favorite',
                'description' => 'Teach 5 different students',
                'icon' => 'fas fa-users',
                'badge_color' => '#dc3545',
                'type' => 'tutor',
                'category' => 'social',
                'points' => 100,
                'requirement_value' => 5,
                'requirement_type' => 'students_taught',
                'is_active' => true,
            ],
            [
                'name' => 'Highly Rated',
                'description' => 'Receive 5 perfect 5-star ratings',
                'icon' => 'fas fa-star',
                'badge_color' => '#ffd700',
                'type' => 'tutor',
                'category' => 'social',
                'points' => 150,
                'requirement_value' => 5,
                'requirement_type' => 'perfect_ratings',
                'is_active' => true,
            ],
            
            // Both Student and Tutor
            [
                'name' => 'Welcome!',
                'description' => 'Join MentorHub platform',
                'icon' => 'fas fa-hand-wave',
                'badge_color' => '#6c757d',
                'type' => 'both',
                'category' => 'milestone',
                'points' => 5,
                'requirement_value' => 1,
                'requirement_type' => null,
                'is_active' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
