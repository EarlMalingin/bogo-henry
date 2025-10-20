<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we don't need to disable foreign key checks
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Get all existing students
        $students = DB::table('students')->get();
        
        // Get all existing tutors
        $tutors = DB::table('tutors')->get();
        
        // Create a map to track email to unified user ID
        $emailToUnifiedId = [];
        
        // First, process all students
        foreach ($students as $student) {
            $unifiedUser = DB::table('unified_users')->where('email', $student->email)->first();
            
            if (!$unifiedUser) {
                // Create new unified user
                $unifiedId = DB::table('unified_users')->insertGetId([
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'email' => $student->email,
                    'password' => $student->password,
                    'phone' => $student->phone,
                    'profile_picture' => $student->profile_picture,
                    'user_type' => 'student',
                    'student_id' => $student->student_id,
                    'year_level' => $student->year_level,
                    'course' => $student->course,
                    'subjects_interest' => $student->subjects_interest,
                    'created_at' => $student->created_at,
                    'updated_at' => $student->updated_at,
                ]);
                
                $emailToUnifiedId[$student->email] = $unifiedId;
            } else {
                // Update existing user to include student data
                DB::table('unified_users')
                    ->where('id', $unifiedUser->id)
                    ->update([
                        'user_type' => $unifiedUser->user_type === 'tutor' ? 'both' : 'student',
                        'student_id' => $student->student_id,
                        'year_level' => $student->year_level,
                        'course' => $student->course,
                        'subjects_interest' => $student->subjects_interest,
                    ]);
                
                $emailToUnifiedId[$student->email] = $unifiedUser->id;
            }
        }
        
        // Then, process all tutors
        foreach ($tutors as $tutor) {
            $unifiedUser = DB::table('unified_users')->where('email', $tutor->email)->first();
            
            if (!$unifiedUser) {
                // Create new unified user
                $unifiedId = DB::table('unified_users')->insertGetId([
                    'first_name' => $tutor->first_name,
                    'last_name' => $tutor->last_name,
                    'email' => $tutor->email,
                    'password' => $tutor->password,
                    'phone' => $tutor->phone,
                    'profile_picture' => $tutor->profile_picture,
                    'user_type' => 'tutor',
                    'tutor_id' => $tutor->tutor_id,
                    'specialization' => $tutor->specialization,
                    'bio' => $tutor->bio,
                    'session_rate' => $tutor->session_rate ?? null,
                    'created_at' => $tutor->created_at,
                    'updated_at' => $tutor->updated_at,
                ]);
                
                $emailToUnifiedId[$tutor->email] = $unifiedId;
            } else {
                // Update existing user to include tutor data
                DB::table('unified_users')
                    ->where('id', $unifiedUser->id)
                    ->update([
                        'user_type' => $unifiedUser->user_type === 'student' ? 'both' : 'tutor',
                        'tutor_id' => $tutor->tutor_id,
                        'specialization' => $tutor->specialization,
                        'bio' => $tutor->bio,
                        'session_rate' => $tutor->session_rate ?? null,
                    ]);
                
                $emailToUnifiedId[$tutor->email] = $unifiedUser->id;
            }
        }
        
        // Update tutoring_sessions table to use unified user IDs
        $sessions = DB::table('tutoring_sessions')->get();
        foreach ($sessions as $session) {
            // Find the unified user ID for the student
            $student = DB::table('students')->where('id', $session->student_id)->first();
            if ($student && isset($emailToUnifiedId[$student->email])) {
                DB::table('tutoring_sessions')
                    ->where('id', $session->id)
                    ->update(['student_id' => $emailToUnifiedId[$student->email]]);
            }
            
            // Find the unified user ID for the tutor
            $tutor = DB::table('tutors')->where('id', $session->tutor_id)->first();
            if ($tutor && isset($emailToUnifiedId[$tutor->email])) {
                DB::table('tutoring_sessions')
                    ->where('id', $session->id)
                    ->update(['tutor_id' => $emailToUnifiedId[$tutor->email]]);
            }
        }
        
        // Update messages table to use unified user IDs
        $messages = DB::table('messages')->get();
        foreach ($messages as $message) {
            // Find the unified user ID for the sender
            $sender = DB::table('students')->where('id', $message->sender_id)->first();
            if (!$sender) {
                $sender = DB::table('tutors')->where('id', $message->sender_id)->first();
            }
            
            if ($sender && isset($emailToUnifiedId[$sender->email])) {
                DB::table('messages')
                    ->where('id', $message->id)
                    ->update(['sender_id' => $emailToUnifiedId[$sender->email]]);
            }
        }
        
        // For SQLite, we don't need to re-enable foreign key checks
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible
        // You would need to manually restore the original tables
    }
};
