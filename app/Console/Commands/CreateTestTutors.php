<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tutor;
use Illuminate\Support\Facades\Hash;

class CreateTestTutors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tutors:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test tutors for smart match testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating test tutors for smart match testing...');
        $this->newLine();

        // Check if test tutors already exist
        $existingCount = Tutor::where('email', 'like', 'test_tutor_%@example.com')->count();
        if ($existingCount > 0) {
            if (!$this->confirm("Found {$existingCount} existing test tutors. Do you want to delete them first?", true)) {
                $this->warn('Keeping existing test tutors. New ones will be created with different emails.');
            } else {
                Tutor::where('email', 'like', 'test_tutor_%@example.com')->delete();
                $this->info('Deleted existing test tutors.');
            }
        }

        // Test tutors with "Coding" specialization (should match student interest)
        $codingTutors = [
            [
                'first_name' => 'John',
                'last_name' => 'Coder',
                'email' => 'test_tutor_coding1@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Coding, Programming, Web Development',
                'bio' => 'Experienced coding tutor specializing in web development and programming.',
                'session_rate' => 500,
                'hourly_rate' => 800,
                'phone' => '09123456781',
                'is_verified' => true,
                'is_active' => true,
                'registration_status' => 'approved',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Developer',
                'email' => 'test_tutor_coding2@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Coding, Software Engineering, Python',
                'bio' => 'Professional software engineer with expertise in Python and software development.',
                'session_rate' => 600,
                'hourly_rate' => 900,
                'phone' => '09123456782',
                'is_verified' => true,
                'is_active' => true,
                'registration_status' => 'approved',
            ],
            [
                'first_name' => 'Mike',
                'last_name' => 'Programmer',
                'email' => 'test_tutor_coding3@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Coding, JavaScript, React',
                'bio' => 'Full-stack developer specializing in modern web technologies.',
                'session_rate' => 550,
                'hourly_rate' => 850,
                'phone' => '09123456783',
                'is_verified' => true,
                'is_active' => true,
                'registration_status' => 'approved',
            ],
        ];

        // Test tutors with different specializations (should NOT match)
        $otherTutors = [
            [
                'first_name' => 'Emily',
                'last_name' => 'Mathematician',
                'email' => 'test_tutor_math@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Mathematics, Algebra, Calculus',
                'bio' => 'Mathematics expert with years of teaching experience.',
                'session_rate' => 450,
                'hourly_rate' => 700,
                'phone' => '09123456784',
                'is_verified' => true,
                'is_active' => true,
                'registration_status' => 'approved',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Scientist',
                'email' => 'test_tutor_science@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'Science, Physics, Chemistry',
                'bio' => 'Science tutor specializing in physics and chemistry.',
                'session_rate' => 500,
                'hourly_rate' => 750,
                'phone' => '09123456785',
                'is_verified' => true,
                'is_active' => true,
                'registration_status' => 'approved',
            ],
            [
                'first_name' => 'Lisa',
                'last_name' => 'Writer',
                'email' => 'test_tutor_english@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'English, Literature, Writing',
                'bio' => 'English literature and writing tutor.',
                'session_rate' => 400,
                'hourly_rate' => 650,
                'phone' => '09123456786',
                'is_verified' => true,
                'is_active' => true,
                'registration_status' => 'approved',
            ],
            [
                'first_name' => 'Robert',
                'last_name' => 'Historian',
                'email' => 'test_tutor_history@example.com',
                'password' => Hash::make('password123'),
                'specialization' => 'History, World History, Social Studies',
                'bio' => 'History tutor with expertise in world history.',
                'session_rate' => 350,
                'hourly_rate' => 600,
                'phone' => '09123456787',
                'is_verified' => true,
                'is_active' => true,
                'registration_status' => 'approved',
            ],
        ];

        // Create coding tutors (matched)
        $this->info('Creating 3 tutors with "Coding" specialization (MATCHED):');
        $this->newLine();
        foreach ($codingTutors as $tutorData) {
            try {
                $tutorId = Tutor::generateTutorId();
                $tutor = Tutor::create(array_merge($tutorData, ['tutor_id' => $tutorId]));
                $this->line("  ✓ Created: {$tutor->first_name} {$tutor->last_name} ({$tutor->email})");
                $this->line("    Specialization: {$tutor->specialization}");
            } catch (\Exception $e) {
                $this->error("  ✗ Failed to create {$tutorData['email']}: {$e->getMessage()}");
            }
        }

        $this->newLine();

        // Create other tutors (not matched)
        $this->info('Creating 4 tutors with different specializations (NOT MATCHED):');
        $this->newLine();
        foreach ($otherTutors as $tutorData) {
            try {
                $tutorId = Tutor::generateTutorId();
                $tutor = Tutor::create(array_merge($tutorData, ['tutor_id' => $tutorId]));
                $this->line("  ✓ Created: {$tutor->first_name} {$tutor->last_name} ({$tutor->email})");
                $this->line("    Specialization: {$tutor->specialization}");
            } catch (\Exception $e) {
                $this->error("  ✗ Failed to create {$tutorData['email']}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('✅ Test tutors created successfully!');
        $this->newLine();
        $this->line('Summary:');
        $this->line('  - 3 tutors with "Coding" specialization (should appear first with "Matched" badge)');
        $this->line('  - 4 tutors with different specializations (should appear after matched tutors)');
        $this->newLine();
        $this->line('To test:');
        $this->line('  1. Login as a student with "Coding" in subjects_interest');
        $this->line('  2. Go to Book Session page');
        $this->line('  3. The 3 coding tutors should appear first with green "Matched" badges');
        $this->newLine();
        $this->line('All test tutors use password: password123');

        return Command::SUCCESS;
    }
}
