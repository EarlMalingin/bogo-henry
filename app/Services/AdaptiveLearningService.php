<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Activity;
use App\Models\ActivitySubmission;
use App\Models\Tutor;

class AdaptiveLearningService
{
    /**
     * Analyze student performance and generate learning recommendations
     */
    public function analyzeStudentPerformance($studentId, $tutorId)
    {
        // Get all graded activities for this student with this tutor
        $activities = Activity::where('student_id', $studentId)
            ->where('tutor_id', $tutorId)
            ->where('status', 'graded')
            ->with(['submissions' => function($query) use ($studentId) {
                $query->where('student_id', $studentId)
                      ->where('status', 'graded');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($activities->isEmpty()) {
            return [
                'learning_pace' => 'normal',
                'recommendations' => [],
                'performance_trend' => 'insufficient_data',
                'average_score' => 0,
                'total_activities' => 0
            ];
        }

        // Calculate performance metrics
        $scores = [];
        $recentScores = [];
        $totalScore = 0;
        $totalPoints = 0;
        $count = 0;

        foreach ($activities as $activity) {
            $submission = $activity->studentSubmission($studentId);
            if ($submission && $submission->score !== null) {
                $percentage = ($submission->score / $activity->total_points) * 100;
                $scores[] = $percentage;
                $totalScore += $percentage;
                $totalPoints += $activity->total_points;
                $count++;

                // Get recent scores (last 5 activities)
                if (count($recentScores) < 5) {
                    $recentScores[] = $percentage;
                }
            }
        }

        $averageScore = $count > 0 ? ($totalScore / $count) : 0;
        
        // Determine learning pace
        $learningPace = $this->determineLearningPace($scores, $recentScores);
        
        // Determine performance trend
        $performanceTrend = $this->determinePerformanceTrend($scores);
        
        // Generate recommendations
        $recommendations = $this->generateRecommendations($averageScore, $learningPace, $performanceTrend, $scores);

        return [
            'learning_pace' => $learningPace,
            'recommendations' => $recommendations,
            'performance_trend' => $performanceTrend,
            'average_score' => round($averageScore, 2),
            'total_activities' => $count,
            'scores' => $scores,
            'recent_scores' => $recentScores
        ];
    }

    /**
     * Determine student's learning pace
     */
    private function determineLearningPace($allScores, $recentScores)
    {
        if (empty($allScores)) {
            return 'normal';
        }

        $overallAvg = array_sum($allScores) / count($allScores);
        $recentAvg = !empty($recentScores) ? array_sum($recentScores) / count($recentScores) : $overallAvg;

        // If recent performance is significantly better, student is learning fast
        if ($recentAvg >= $overallAvg + 10) {
            return 'fast';
        }
        
        // If recent performance is significantly worse, student is struggling
        if ($recentAvg <= $overallAvg - 10) {
            return 'slow';
        }

        // If average is high, student is advanced
        if ($overallAvg >= 85) {
            return 'advanced';
        }

        // If average is low, student needs more support
        if ($overallAvg < 60) {
            return 'needs_support';
        }

        return 'normal';
    }

    /**
     * Determine performance trend
     */
    private function determinePerformanceTrend($scores)
    {
        if (count($scores) < 3) {
            return 'insufficient_data';
        }

        // Get first half and second half of scores
        $midPoint = floor(count($scores) / 2);
        $firstHalf = array_slice($scores, 0, $midPoint);
        $secondHalf = array_slice($scores, $midPoint);

        $firstAvg = array_sum($firstHalf) / count($firstHalf);
        $secondAvg = array_sum($secondHalf) / count($secondHalf);

        if ($secondAvg > $firstAvg + 5) {
            return 'improving';
        } elseif ($secondAvg < $firstAvg - 5) {
            return 'declining';
        }

        return 'stable';
    }

    /**
     * Generate personalized recommendations for tutor
     */
    private function generateRecommendations($averageScore, $learningPace, $performanceTrend, $scores)
    {
        $recommendations = [];

        // Base recommendations on average score
        if ($averageScore >= 90) {
            $recommendations[] = [
                'type' => 'success',
                'icon' => 'fas fa-rocket',
                'title' => 'Excellent Performance',
                'message' => 'Student is performing excellently. Consider assigning more challenging activities to maintain engagement.',
                'action' => 'Increase difficulty level and introduce advanced topics'
            ];
        } elseif ($averageScore >= 80) {
            $recommendations[] = [
                'type' => 'info',
                'icon' => 'fas fa-chart-line',
                'title' => 'Good Performance',
                'message' => 'Student is performing well. Maintain current pace and gradually introduce more complex concepts.',
                'action' => 'Continue current approach with slight difficulty increase'
            ];
        } elseif ($averageScore >= 70) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => 'fas fa-lightbulb',
                'title' => 'Average Performance',
                'message' => 'Student is performing at average level. Focus on reinforcing core concepts before advancing.',
                'action' => 'Review fundamental concepts and provide additional practice'
            ];
        } elseif ($averageScore >= 60) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'Below Average Performance',
                'message' => 'Student needs additional support. Break down complex topics into smaller, manageable parts.',
                'action' => 'Simplify content and provide step-by-step guidance'
            ];
        } else {
            $recommendations[] = [
                'type' => 'danger',
                'icon' => 'fas fa-hand-paper',
                'title' => 'Needs Immediate Attention',
                'message' => 'Student is struggling significantly. Consider one-on-one sessions and foundational review.',
                'action' => 'Schedule extra sessions and review basics thoroughly'
            ];
        }

        // Add pace-specific recommendations
        if ($learningPace === 'fast') {
            $recommendations[] = [
                'type' => 'success',
                'icon' => 'fas fa-tachometer-alt',
                'title' => 'Fast Learner Detected',
                'message' => 'Student is learning quickly. You can accelerate the curriculum and introduce advanced materials.',
                'action' => 'Increase activity frequency and difficulty'
            ];
        } elseif ($learningPace === 'slow') {
            $recommendations[] = [
                'type' => 'info',
                'icon' => 'fas fa-clock',
                'title' => 'Learning Pace Adjustment Needed',
                'message' => 'Student may need more time to process information. Consider reducing activity frequency and providing more examples.',
                'action' => 'Slow down pace and provide more practice opportunities'
            ];
        } elseif ($learningPace === 'needs_support') {
            $recommendations[] = [
                'type' => 'danger',
                'icon' => 'fas fa-life-ring',
                'title' => 'Additional Support Required',
                'message' => 'Student needs extra help. Consider breaking lessons into smaller chunks and providing more detailed explanations.',
                'action' => 'Increase support sessions and simplify content structure'
            ];
        }

        // Add trend-specific recommendations
        if ($performanceTrend === 'improving') {
            $recommendations[] = [
                'type' => 'success',
                'icon' => 'fas fa-arrow-up',
                'title' => 'Positive Trend',
                'message' => 'Student performance is improving. Continue with current teaching approach.',
                'action' => 'Maintain current teaching methods'
            ];
        } elseif ($performanceTrend === 'declining') {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => 'fas fa-arrow-down',
                'title' => 'Performance Decline',
                'message' => 'Student performance is declining. Review recent topics and identify areas of difficulty.',
                'action' => 'Revisit recent topics and adjust teaching strategy'
            ];
        }

        // Add consistency recommendations
        if (count($scores) >= 5) {
            $variance = $this->calculateVariance($scores);
            if ($variance > 400) { // High variance (inconsistent performance)
                $recommendations[] = [
                    'type' => 'warning',
                    'icon' => 'fas fa-chart-area',
                    'title' => 'Inconsistent Performance',
                    'message' => 'Student performance varies significantly. Identify patterns in topics where student struggles.',
                    'action' => 'Focus on weak areas and provide targeted practice'
                ];
            }
        }

        return $recommendations;
    }

    /**
     * Calculate variance in scores
     */
    private function calculateVariance($scores)
    {
        if (count($scores) < 2) {
            return 0;
        }

        $mean = array_sum($scores) / count($scores);
        $variance = 0;

        foreach ($scores as $score) {
            $variance += pow($score - $mean, 2);
        }

        return $variance / count($scores);
    }

    /**
     * Get suggested activity difficulty based on performance
     */
    public function getSuggestedDifficulty($studentId, $tutorId)
    {
        $analysis = $this->analyzeStudentPerformance($studentId, $tutorId);
        
        $averageScore = $analysis['average_score'];
        
        if ($averageScore >= 90) {
            return 'advanced';
        } elseif ($averageScore >= 80) {
            return 'intermediate';
        } elseif ($averageScore >= 70) {
            return 'basic';
        } else {
            return 'foundational';
        }
    }

    /**
     * Get suggested activity frequency based on learning pace
     */
    public function getSuggestedFrequency($studentId, $tutorId)
    {
        $analysis = $this->analyzeStudentPerformance($studentId, $tutorId);
        
        $learningPace = $analysis['learning_pace'];
        
        switch ($learningPace) {
            case 'fast':
            case 'advanced':
                return 'daily'; // Can handle more activities
            case 'normal':
                return 'every_other_day'; // Standard pace
            case 'slow':
            case 'needs_support':
                return 'weekly'; // Needs more time between activities
            default:
                return 'every_other_day';
        }
    }
}

