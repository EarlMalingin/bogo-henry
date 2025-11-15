<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('style/dashboard.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>{{ $activity->title }} | MentorHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .activity-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .activity-header {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .activity-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .activity-meta {
            display: flex;
            gap: 2rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #666;
        }

        .activity-type {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .type-activity { background-color: #e3f2fd; color: #1976d2; }
        .type-exam { background-color: #fff3e0; color: #f57c00; }
        .type-assignment { background-color: #f3e5f5; color: #7b1fa2; }
        .type-quiz { background-color: #e8f5e8; color: #388e3c; }

        .activity-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .instructions-section {
            background-color: #f8f9fa;
            border-left: 4px solid #4a90e2;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-radius: 0 5px 5px 0;
        }

        .instructions-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }

        .instructions-content {
            color: #666;
            line-height: 1.6;
        }

        .submission-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .submission-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .student-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .student-details h3 {
            margin: 0;
            color: #333;
        }

        .student-details p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .submission-status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-draft { background-color: #f8f9fa; color: #6c757d; }
        .status-submitted { background-color: #fff3cd; color: #856404; }
        .status-graded { background-color: #d4edda; color: #155724; }

        .submission-content {
            margin-bottom: 2rem;
        }

        .answers-section {
            margin-bottom: 2rem;
        }

        .answer-item {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .answer-question {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .answer-text {
            color: #666;
            line-height: 1.6;
        }

        .attachments-section {
            margin-bottom: 2rem;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .attachment-icon {
            color: #4a90e2;
            font-size: 1.5rem;
        }

        .attachment-info {
            flex: 1;
        }

        .attachment-name {
            font-weight: 600;
            color: #333;
        }

        .download-btn {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .grading-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .grading-form {
            display: grid;
            gap: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group textarea {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            padding: 0.8rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }

        .btn-primary:hover {
            background-color: #3a7ccc;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .back-btn {
            margin-bottom: 1rem;
            margin-top: 1rem;
        }

        .no-submission {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .no-submission i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .activity-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .submission-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .grading-form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <main>
        <div class="activity-container">
            <div class="back-btn">
                <a href="{{ route('tutor.my-sessions') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to My Sessions
                </a>
            </div>

            <!-- Activity Header -->
            <div class="activity-header">
                <h1 class="activity-title">{{ $activity->title }}</h1>
                <div class="activity-type type-{{ $activity->type }}">{{ $activity->type }}</div>
                
                <div class="activity-meta">
                    <span><i class="fas fa-user"></i> Student: {{ $activity->student->first_name }} {{ $activity->student->last_name }}</span>
                    <span><i class="fas fa-calendar"></i> Created: {{ $activity->created_at->format('M d, Y') }}</span>
                    @if($activity->due_date)
                        <span><i class="fas fa-clock"></i> Due: {{ $activity->due_date->format('M d, Y g:i A') }}</span>
                    @endif
                    @if($activity->time_limit)
                        <span><i class="fas fa-stopwatch"></i> Time Limit: {{ $activity->time_limit }} minutes</span>
                    @endif
                </div>

                <div class="activity-description">
                    {{ $activity->description }}
                </div>

                @if($activity->instructions)
                    <div class="instructions-section">
                        <div class="instructions-title">
                            <i class="fas fa-info-circle"></i> Instructions
                        </div>
                        <div class="instructions-content">
                            {{ $activity->instructions }}
                        </div>
                    </div>
                @endif
            </div>

            @php
                $submission = $activity->submissions()->where('student_id', $activity->student_id)->first();
            @endphp

            @if($submission)
                <!-- Student Submission -->
                <div class="submission-section">
                    <div class="submission-header">
                        <div class="student-info">
                            <div class="student-avatar">
                                @if($activity->student->profile_picture)
                                    <img src="{{ asset('storage/' . $activity->student->profile_picture) }}" alt="Student" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                @else
                                    {{ strtoupper(substr($activity->student->first_name, 0, 1) . substr($activity->student->last_name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="student-details">
                                <h3>{{ $activity->student->first_name }} {{ $activity->student->last_name }}</h3>
                                <p>{{ $activity->student->course }} - Year {{ $activity->student->year_level }}</p>
                            </div>
                        </div>
                        <div class="submission-status status-{{ $submission->status }}">
                            {{ ucfirst($submission->status) }}
                        </div>
                    </div>

                    @if($submission->status === 'submitted' || $submission->status === 'graded')
                        <div class="submission-content">
                            @if($submission->answers && count($submission->answers) > 0)
                                <div class="answers-section">
                                    <h3 style="margin-bottom: 1rem; color: #333;">Student Answers</h3>
                                    @foreach($submission->answers as $index => $answer)
                                        <div class="answer-item">
                                            <div class="answer-question">Question {{ $index + 1 }}</div>
                                            <div class="answer-text">{{ $answer }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if($submission->attachments && count($submission->attachments) > 0)
                                <div class="attachments-section">
                                    <h3 style="margin-bottom: 1rem; color: #333;">Student Attachments</h3>
                                    @foreach($submission->attachments as $attachment)
                                        <div class="attachment-item">
                                            <i class="fas fa-file attachment-icon"></i>
                                            <div class="attachment-info">
                                                <div class="attachment-name">{{ basename($attachment) }}</div>
                                            </div>
                                            <a href="{{ route('tutor.activities.download-submission', ['activity' => $activity->id, 'attachment' => base64_encode($attachment)]) }}" 
                                               class="download-btn" 
                                               download>
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if($submission->notes)
                                <div class="notes-section">
                                    <h3 style="margin-bottom: 1rem; color: #333;">Student Notes</h3>
                                    <div class="answer-item">
                                        <div class="answer-text">{{ $submission->notes }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($submission->status === 'submitted')
                            <!-- Grading Section -->
                            <div class="grading-section">
                                <h3 style="margin-bottom: 1.5rem; color: #333;">Grade This Activity</h3>
                                <form id="grade-form" method="POST" action="{{ route('tutor.activities.grade', $activity) }}">
                                    @csrf
                                    <div class="grading-form">
                                        <div class="form-group">
                                            <label for="score">Score (out of {{ $activity->total_points }} points)</label>
                                            <input type="number" 
                                                   id="score" 
                                                   name="score" 
                                                   min="0" 
                                                   max="{{ $activity->total_points }}" 
                                                   value="{{ $submission->score ?? '' }}"
                                                   required>
                                        </div>
                                        <div class="form-group">
                                            <label for="feedback">Feedback</label>
                                            <textarea id="feedback" 
                                                      name="feedback" 
                                                      placeholder="Provide feedback to the student...">{{ $submission->feedback ?? '' }}</textarea>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary" id="submit-grade-btn">
                                                <i class="fas fa-check"></i> Submit Grade
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @elseif($submission->status === 'graded')
                            <!-- Graded Results -->
                            <div class="grading-section">
                                <h3 style="margin-bottom: 1rem; color: #333;">Grading Results</h3>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                                    <div style="text-align: center; padding: 1rem; background-color: white; border-radius: 5px;">
                                        <div style="font-size: 2rem; font-weight: bold; color: #4a90e2;">{{ $submission->score }}</div>
                                        <div style="color: #666;">Score</div>
                                    </div>
                                    <div style="text-align: center; padding: 1rem; background-color: white; border-radius: 5px;">
                                        <div style="font-size: 2rem; font-weight: bold; color: #4a90e2;">{{ $activity->total_points }}</div>
                                        <div style="color: #666;">Total Points</div>
                                    </div>
                                    <div style="text-align: center; padding: 1rem; background-color: white; border-radius: 5px;">
                                        <div style="font-size: 2rem; font-weight: bold; color: #4a90e2;">{{ round(($submission->score / $activity->total_points) * 100) }}%</div>
                                        <div style="color: #666;">Percentage</div>
                                    </div>
                                </div>
                                @if($submission->feedback)
                                    <div style="background-color: white; padding: 1rem; border-radius: 5px;">
                                        <h4 style="color: #333; margin-bottom: 0.5rem;">Feedback:</h4>
                                        <p style="color: #666; line-height: 1.6;">{{ $submission->feedback }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="no-submission">
                            <i class="fas fa-clock"></i>
                            <h3>Waiting for Submission</h3>
                            <p>The student hasn't submitted this activity yet.</p>
                        </div>
                    @endif
                </div>
            @else
                <!-- No Submission -->
                <div class="submission-section">
                    <div class="no-submission">
                        <i class="fas fa-user-clock"></i>
                        <h3>No Submission Yet</h3>
                        <p>The student hasn't started working on this activity.</p>
                    </div>
                </div>
            @endif
        </div>
    </main>
    
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">FAQ</a>
                <a href="#">Contact</a>
            </div>
            <div class="copyright">
                &copy; 2025 MentorHub. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Success Modal -->
    <div id="successModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 500px; text-align: center;">
            <div style="font-size: 3rem; color: #10b981; margin-bottom: 1rem;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="color: #333; margin-bottom: 1rem;">Activity Graded Successfully!</h2>
            <p style="color: #666; margin-bottom: 2rem;">The student has been notified of their grade.</p>
            <button onclick="redirectToMySessions()" class="btn btn-primary" style="width: 100%;">
                Go to My Sessions
            </button>
        </div>
    </div>

    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
    </style>

    <script>
        const gradeForm = document.getElementById('grade-form');
        if (gradeForm) {
            gradeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const formData = new FormData(form);
                const submitBtn = document.getElementById('submit-grade-btn');
                
                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Grading...';
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success modal
                        document.getElementById('successModal').style.display = 'flex';
                    } else {
                        throw new Error(data.message || 'Grading failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while grading. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Submit Grade';
                });
            });
        }

        function redirectToMySessions() {
            window.location.href = "{{ route('tutor.my-sessions') }}";
        }
    </script>
</body>
</html>
