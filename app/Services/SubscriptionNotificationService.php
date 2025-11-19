<?php

namespace App\Services;

use App\Models\Session;
use App\Models\Notification;
use App\Mail\SubscriptionExpiringMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SubscriptionNotificationService
{
    /**
     * Check for expiring monthly subscriptions and create notifications
     */
    public function checkExpiringSubscriptions($studentId = null)
    {
        // Get all active monthly subscriptions
        // Check for booking_type = 'monthly' OR identify by rate matching monthly rate and full day duration
        $query = Session::where('status', 'accepted')
            ->where(function($q) {
                $q->where('booking_type', 'monthly')
                  ->orWhere(function($subQ) {
                      // For backward compatibility: identify monthly by start_time = 00:00:00 and end_time = 23:59:59
                      $subQ->where('start_time', '00:00:00')
                           ->where('end_time', '23:59:59');
                  });
            })
            ->with(['student', 'tutor']);

        // If student ID is provided, filter by student
        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $monthlySubscriptions = $query->get();

        $notificationsCreated = 0;
        $today = Carbon::today();
        $sevenDaysFromNow = Carbon::today()->addDays(7);

        foreach ($monthlySubscriptions as $session) {
            // Calculate subscription end date (1 month from start date)
            $subscriptionEndDate = Carbon::parse($session->date)->copy()->addMonth();
            
            // Check if subscription expires within 7 days (inclusive)
            if ($subscriptionEndDate->gte($today) && $subscriptionEndDate->lte($sevenDaysFromNow)) {
                $daysRemaining = $today->diffInDays($subscriptionEndDate);
                
                // Check if notification already exists for this subscription (created today)
                $existingNotification = Notification::where('user_id', $session->student_id)
                    ->where('user_type', 'student')
                    ->where('type', 'subscription_expiring')
                    ->where('message', 'like', '%' . $session->tutor->first_name . ' ' . $session->tutor->last_name . '%')
                    ->whereDate('created_at', Carbon::today())
                    ->first();

                if (!$existingNotification) {
                    // Create in-app notification
                    Notification::create([
                        'user_id' => $session->student_id,
                        'user_type' => 'student',
                        'type' => 'subscription_expiring',
                        'title' => 'Monthly Subscription Expiring Soon',
                        'message' => "Your monthly subscription with {$session->tutor->first_name} {$session->tutor->last_name} will expire in {$daysRemaining} day(s). Renew now to continue your sessions!",
                    ]);
                    
                    // Send email notification
                    try {
                        if ($session->student && $session->student->email) {
                            Mail::to($session->student->email)->send(
                                new SubscriptionExpiringMail(
                                    $session->student,
                                    $session->tutor,
                                    $daysRemaining,
                                    $subscriptionEndDate
                                )
                            );
                        }
                    } catch (\Exception $e) {
                        // Log email error but don't fail the notification creation
                        \Log::error('Failed to send subscription expiring email: ' . $e->getMessage());
                    }
                    
                    $notificationsCreated++;
                }
            }
        }

        return $notificationsCreated;
    }
}

