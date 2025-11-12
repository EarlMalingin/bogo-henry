<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Message;
use App\Models\Session;
use Illuminate\Support\Facades\Hash;

class ChatTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test students
        $student1 = Student::updateOrCreate(
            ['email' => 'john.doe@student.com'],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'password' => Hash::make('password123'),
                'student_id' => 'STU001',
                'year_level' => '2nd Year',
                'course' => 'Computer Science',
                'subjects_interest' => 'Programming, Mathematics',
                'phone' => '1234567890'
            ]
        );

        $student2 = Student::updateOrCreate(
            ['email' => 'jane.smith@student.com'],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'password' => Hash::make('password123'),
                'student_id' => 'STU002',
                'year_level' => '1st Year',
                'course' => 'Physics',
                'subjects_interest' => 'Physics, Mathematics',
                'phone' => '1234567891'
            ]
        );

        // Create test tutors
        $tutor1 = Tutor::updateOrCreate(
            ['email' => 'sarah.johnson@tutor.com'],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'password' => Hash::make('password123'),
                'tutor_id' => 'TUT001',
                'specialization' => 'Mathematics, Calculus, Algebra',
                'phone' => '1234567892',
                'bio' => 'Experienced mathematics tutor with 5 years of teaching experience.',
                'session_rate' => 50.00,
                'hourly_rate' => 120.00
            ]
        );

        $tutor2 = Tutor::updateOrCreate(
            ['email' => 'mike.davis@tutor.com'],
            [
                'first_name' => 'Mike',
                'last_name' => 'Davis',
                'password' => Hash::make('password123'),
                'tutor_id' => 'TUT002',
                'specialization' => 'Physics, Chemistry, Biology',
                'phone' => '1234567893',
                'bio' => 'Science tutor specializing in physics and chemistry.',
                'session_rate' => 45.00,
                'hourly_rate' => 110.00
            ]
        );

        // Create test sessions
        $session1 = Session::updateOrCreate(
            [
                'student_id' => $student1->id,
                'tutor_id' => $tutor1->id,
                'date' => now()->addDays(2),
                'start_time' => '14:00:00'
            ],
            [
                'session_type' => 'online',
                'end_time' => '15:00:00',
                'status' => 'accepted',
                'notes' => 'Help with calculus derivatives',
                'rate' => $tutor1->session_rate
            ]
        );

        $session2 = Session::updateOrCreate(
            [
                'student_id' => $student2->id,
                'tutor_id' => $tutor2->id,
                'date' => now()->addDays(3),
                'start_time' => '16:00:00'
            ],
            [
                'session_type' => 'face_to_face',
                'end_time' => '17:00:00',
                'status' => 'accepted',
                'notes' => 'Physics problem solving',
                'rate' => $tutor2->session_rate
            ]
        );

        // Create test messages (only if they don't exist)
        $existingMessages = Message::where('sender_id', $student1->id)
            ->where('receiver_id', $tutor1->id)
            ->count();

        if ($existingMessages == 0) {
            Message::create([
                'sender_id' => $student1->id,
                'sender_type' => 'student',
                'receiver_id' => $tutor1->id,
                'receiver_type' => 'tutor',
                'message' => 'Hi Sarah! I have a question about calculus derivatives. Can you help me understand the chain rule?',
                'is_read' => true,
                'created_at' => now()->subHours(2)
            ]);

            Message::create([
                'sender_id' => $tutor1->id,
                'sender_type' => 'tutor',
                'receiver_id' => $student1->id,
                'receiver_type' => 'student',
                'message' => 'Of course! I\'d be happy to help you with the chain rule. What specific concept are you having trouble with?',
                'is_read' => false,
                'created_at' => now()->subHours(1)
            ]);

            Message::create([
                'sender_id' => $student1->id,
                'sender_type' => 'student',
                'receiver_id' => $tutor1->id,
                'receiver_type' => 'tutor',
                'message' => 'I\'m confused about when to use it and how to apply it step by step.',
                'is_read' => false,
                'created_at' => now()->subMinutes(30)
            ]);

            Message::create([
                'sender_id' => $student2->id,
                'sender_type' => 'student',
                'receiver_id' => $tutor2->id,
                'receiver_type' => 'tutor',
                'message' => 'Hi Mike! Can we reschedule our session for tomorrow? I have a conflict today.',
                'is_read' => true,
                'created_at' => now()->subHours(3)
            ]);

            Message::create([
                'sender_id' => $tutor2->id,
                'sender_type' => 'tutor',
                'receiver_id' => $student2->id,
                'receiver_type' => 'student',
                'message' => 'No problem! What time works better for you tomorrow?',
                'is_read' => false,
                'created_at' => now()->subHours(2)
            ]);
        }

        $this->command->info('Test data created successfully!');
        $this->command->info('Students: john.doe@student.com, jane.smith@student.com (password: password123)');
        $this->command->info('Tutors: sarah.johnson@tutor.com, mike.davis@tutor.com (password: password123)');
    }
}
