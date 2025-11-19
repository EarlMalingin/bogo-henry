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
            max-width: 1000px;
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

        .questions-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .question-item {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e9ecef;
        }

        .question-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .question-number {
            font-weight: 600;
            color: #4a90e2;
            margin-bottom: 0.5rem;
        }

        .question-text {
            color: #333;
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .answer-input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .answer-input:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .answer-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .attachments-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 2rem;
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

        .attachment-item:last-child {
            margin-bottom: 0;
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

        .attachment-size {
            font-size: 0.9rem;
            color: #666;
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

        .download-btn:hover {
            background-color: #3a7ccc;
        }

        .activity-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
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

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .back-btn {
            margin-bottom: 1rem;
            margin-top: 1rem;
        }

        .progress-info {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .progress-icon {
            color: #1976d2;
            font-size: 1.5rem;
        }

        .progress-text {
            color: #1976d2;
            font-weight: 500;
        }

        /* File Upload Styles */
        .file-upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            background-color: #fafafa;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .file-upload-area:hover {
            border-color: #4a90e2;
            background-color: #f0f8ff;
        }

        .file-upload-area.dragover {
            border-color: #4a90e2;
            background-color: #e3f2fd;
        }

        .upload-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .upload-icon {
            font-size: 3rem;
            color: #4a90e2;
        }

        .upload-text {
            font-size: 1.1rem;
            color: #333;
            margin: 0;
        }

        .upload-hint {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }

        .file-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .file-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }

        .file-icon {
            color: #4a90e2;
            font-size: 1.5rem;
        }

        .file-info {
            flex: 1;
        }

        .file-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .file-size {
            font-size: 0.9rem;
            color: #666;
        }

        .remove-file-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .remove-file-btn:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            .activity-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .activity-actions {
                flex-direction: column;
            }

            .file-upload-area {
                padding: 1.5rem;
            }

            .upload-icon {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <main>
        <div class="activity-container">
            <div class="back-btn">
                <a href="{{ route('student.tutor.activities', $activity->tutor) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Activities
                </a>
            </div>

            <!-- Activity Header -->
            <div class="activity-header">
                <h1 class="activity-title">{{ $activity->title }}</h1>
                <div class="activity-type type-{{ $activity->type }}">{{ $activity->type }}</div>
                
                <div class="activity-meta">
                    <span><i class="fas fa-user"></i> Tutor: {{ $activity->tutor->first_name }} {{ $activity->tutor->last_name }}</span>
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

            @if($submission && ($submission->status === 'graded' || ($submission->status === 'submitted' && $submission->score !== null)))
                <!-- Graded Results -->
                <div class="grading-section" style="background-color: #e8f5e9; margin-bottom: 2rem; border-radius: 8px; padding: 2rem;">
                    <h3 style="margin-bottom: 1.5rem; color: #2e7d32;">
                        <i class="fas fa-check-circle" style="color: #4caf50; margin-right: 0.5rem;"></i>
                        Activity Automatically Graded
                    </h3>
                    <p style="color: #2e7d32; margin-bottom: 1rem; font-size: 0.95rem;">
                        <i class="fas fa-info-circle"></i> Your answers have been automatically graded. See your score below.
                    </p>
                    
                    @if($submission->score !== null)
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="text-align: center; padding: 1.5rem; background-color: white; border-radius: 8px;">
                                <div style="font-size: 2.5rem; font-weight: bold; color: #4a90e2;">{{ $submission->score }}</div>
                                <div style="color: #666; margin-top: 0.5rem;">Your Score</div>
                            </div>
                            <div style="text-align: center; padding: 1.5rem; background-color: white; border-radius: 8px;">
                                <div style="font-size: 2.5rem; font-weight: bold; color: #4a90e2;">{{ $activity->total_points }}</div>
                                <div style="color: #666; margin-top: 0.5rem;">Total Points</div>
                            </div>
                            <div style="text-align: center; padding: 1.5rem; background-color: white; border-radius: 8px;">
                                <div style="font-size: 2.5rem; font-weight: bold; color: #4a90e2;">{{ $activity->total_points > 0 ? round(($submission->score / $activity->total_points) * 100) : 0 }}%</div>
                                <div style="color: #666; margin-top: 0.5rem;">Percentage</div>
                            </div>
                        </div>
                    @endif
                    
                    @if($submission->feedback)
                        <div style="background-color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <h4 style="color: #333; margin-bottom: 1rem; display: flex; align-items: center;">
                                <i class="fas fa-comment-alt" style="color: #4a90e2; margin-right: 0.5rem;"></i>
                                Tutor's Feedback:
                            </h4>
                            <p style="color: #666; line-height: 1.8; font-size: 1rem; white-space: pre-wrap;">{{ $submission->feedback }}</p>
                        </div>
                    @endif
                </div>
            @elseif($submission && $submission->status === 'submitted')
                <!-- Submitted Status (should not appear with auto-grading, but kept as fallback) -->
                <div class="progress-info" style="background-color: #fff3cd; border: 1px solid #ffc107;">
                    <i class="fas fa-clock progress-icon" style="color: #856404;"></i>
                    <div class="progress-text" style="color: #856404;">
                        Activity submitted. Processing your score...
                    </div>
                </div>
            @else
                <!-- In Progress Status -->
                <div class="progress-info">
                    <i class="fas fa-edit progress-icon"></i>
                    <div class="progress-text">
                        {{ $submission ? 'Continue working on your answers below.' : 'Start answering the questions below.' }}
                    </div>
                </div>
            @endif

            @if(!$submission || $submission->status !== 'graded')
            <!-- Main Form -->
            <form id="activity-form" method="POST" action="{{ route('student.activities.save-draft', $activity) }}">
                @csrf
                
                @if($activity->questions && count($activity->questions) > 0)
                    <!-- Questions Section -->
                    <div class="questions-section">
                        <h2 style="margin-bottom: 2rem; color: #333;">Questions</h2>
                        
                        @foreach($activity->questions as $index => $question)
                            <div class="question-item">
                                <div class="question-number">Question {{ $index + 1 }}</div>
                                <div class="question-text">{{ $question['question'] ?? $question }}</div>
                                
                                @if(isset($question['type']) && $question['type'] === 'multiple_choice')
                                    <!-- Multiple Choice -->
                                    @if(isset($question['options']))
                                        <div style="margin-top: 1rem;">
                                            @foreach($question['options'] as $optionIndex => $option)
                                                @php
                                                    $optionLabel = chr(65 + $optionIndex); // A, B, C, D, etc.
                                                @endphp
                                                <label style="display: flex; align-items: center; margin-bottom: 0.75rem; padding: 0.75rem; background-color: #f8f9fa; border-radius: 5px; cursor: pointer; transition: background-color 0.2s;">
                                                    <input type="radio" 
                                                           name="answers[{{ $index }}]" 
                                                           value="{{ $optionIndex }}"
                                                           {{ ($submission && isset($submission->answers[$index]) && $submission->answers[$index] == $optionIndex) ? 'checked' : '' }}
                                                           style="margin-right: 0.75rem; cursor: pointer;">
                                                    <span style="font-weight: 600; color: #2d7dd2; min-width: 25px; margin-right: 0.5rem;">{{ $optionLabel }}.</span>
                                                    <span style="flex: 1; font-size: 1rem;">{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <!-- Text Answer -->
                                    <textarea name="answers[{{ $index }}]" 
                                              class="answer-input answer-textarea" 
                                              placeholder="Enter your answer here...">{{ $submission && isset($submission->answers[$index]) ? $submission->answers[$index] : '' }}</textarea>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <!-- Notes Section -->
                <div class="notes-section" style="margin-top: 2rem;">
                    <h3 style="margin-bottom: 1rem; color: #333;">Additional Notes (Optional)</h3>
                    <textarea name="notes" 
                              class="answer-input answer-textarea" 
                              placeholder="Add any additional notes or comments here...">{{ $submission && $submission->notes ? $submission->notes : '' }}</textarea>
                </div>
            </form>
            @else
                <!-- Show submitted answers when graded -->
                @if($activity->questions && count($activity->questions) > 0)
                    <div class="questions-section">
                        <h2 style="margin-bottom: 2rem; color: #333;">Questions and Your Answers</h2>
                        
                        @foreach($activity->questions as $index => $question)
                            @php
                                $questionText = $question['question'] ?? $question;
                                $studentAnswerIndex = isset($submission->answers[$index]) ? (int)$submission->answers[$index] : null;
                                $correctAnswerIndex = isset($question['correct_answer']) ? (int)$question['correct_answer'] : null;
                                $isCorrect = $studentAnswerIndex !== null && $correctAnswerIndex !== null && $studentAnswerIndex === $correctAnswerIndex;
                            @endphp
                            
                            <div class="question-item" style="margin-bottom: 1.5rem; padding: 1.5rem; background-color: {{ $isCorrect ? '#e8f5e9' : '#ffebee' }}; border-left: 4px solid {{ $isCorrect ? '#4caf50' : '#f44336' }}; border-radius: 5px;">
                                <div class="question-number" style="font-weight: 600; color: #333; margin-bottom: 0.75rem; font-size: 1.1rem;">
                                    Question {{ $index + 1 }}
                                    @if($isCorrect)
                                        <span style="color: #4caf50; margin-left: 0.5rem;">
                                            <i class="fas fa-check-circle"></i> Correct
                                        </span>
                                    @else
                                        <span style="color: #f44336; margin-left: 0.5rem;">
                                            <i class="fas fa-times-circle"></i> Incorrect
                                        </span>
                                    @endif
                                </div>
                                
                                <div style="margin-bottom: 1rem; color: #555; line-height: 1.6;">
                                    <strong>Question:</strong> {{ $questionText }}
                                </div>
                                
                                @if(isset($question['type']) && $question['type'] === 'multiple_choice' && isset($question['options']))
                                    <div style="margin-bottom: 0.75rem;">
                                        <strong style="color: #333;">Options:</strong>
                                        <div style="margin-top: 0.5rem;">
                                            @foreach($question['options'] as $optIndex => $option)
                                                @php
                                                    $optionLabel = chr(65 + $optIndex); // A, B, C, D, etc.
                                                    $isStudentAnswer = $studentAnswerIndex === $optIndex;
                                                    $isCorrectAnswer = $correctAnswerIndex === $optIndex;
                                                @endphp
                                                <div style="padding: 0.5rem; margin-bottom: 0.5rem; background-color: white; border-radius: 3px; border: 2px solid {{ $isStudentAnswer ? ($isCorrect ? '#4caf50' : '#f44336') : ($isCorrectAnswer ? '#4caf50' : '#e0e0e0') }};">
                                                    <span style="font-weight: 600; color: #2d7dd2; margin-right: 0.5rem;">{{ $optionLabel }}.</span>
                                                    <span style="color: #333;">{{ $option }}</span>
                                                    @if($isStudentAnswer)
                                                        <span style="color: {{ $isCorrect ? '#4caf50' : '#f44336' }}; margin-left: 0.5rem; font-weight: 600;">
                                                            <i class="fas fa-arrow-left"></i> Your Answer
                                                        </span>
                                                    @endif
                                                    @if($isCorrectAnswer && !$isStudentAnswer)
                                                        <span style="color: #4caf50; margin-left: 0.5rem; font-weight: 600;">
                                                            <i class="fas fa-check"></i> Correct Answer
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    @if($studentAnswerIndex !== null)
                                        <div style="padding: 0.75rem; background-color: white; border-radius: 3px; margin-top: 0.5rem;">
                                            <strong style="color: #333;">You Selected:</strong>
                                            <span style="color: #2d7dd2; font-weight: 600; margin-left: 0.5rem;">
                                                {{ chr(65 + $studentAnswerIndex) }}. {{ $question['options'][$studentAnswerIndex] ?? 'N/A' }}
                                            </span>
                                        </div>
                                    @else
                                        <div style="padding: 0.75rem; background-color: #fff3cd; border-radius: 3px; margin-top: 0.5rem; color: #856404;">
                                            <i class="fas fa-exclamation-triangle"></i> No answer provided
                                        </div>
                                    @endif
                                @else
                                    @if(isset($submission->answers[$index]))
                                        <div style="padding: 0.75rem; background-color: white; border-radius: 3px; margin-top: 0.5rem;">
                                            <strong style="color: #333;">Your Answer:</strong>
                                            <div style="color: #555; margin-top: 0.5rem; white-space: pre-wrap;">{{ $submission->answers[$index] }}</div>
                                        </div>
                                    @else
                                        <div style="padding: 0.75rem; background-color: #fff3cd; border-radius: 3px; margin-top: 0.5rem; color: #856404;">
                                            <i class="fas fa-exclamation-triangle"></i> No answer provided
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
                
                @if($submission && $submission->notes)
                    <div class="notes-section" style="margin-top: 2rem;">
                        <h3 style="margin-bottom: 1rem; color: #333;">Your Additional Notes</h3>
                        <div style="padding: 1rem; background-color: #f8f9fa; border-radius: 5px;">
                            <p style="color: #666; white-space: pre-wrap;">{{ $submission->notes }}</p>
                        </div>
                    </div>
                @endif
            @endif

            @if($activity->attachments && count($activity->attachments) > 0)
                <!-- Tutor Attachments Section -->
                <div class="attachments-section">
                    <h2 style="margin-bottom: 1.5rem; color: #333;">Tutor Attachments</h2>
                    
                    @foreach($activity->attachments as $attachment)
                        <div class="attachment-item">
                            <i class="fas fa-file attachment-icon"></i>
                            <div class="attachment-info">
                                <div class="attachment-name">{{ basename($attachment) }}</div>
                                <div class="attachment-size">Download file</div>
                            </div>
                            <a href="{{ route('student.activities.download-attachment', ['activity' => $activity->id, 'attachment' => base64_encode($attachment)]) }}" 
                               class="download-btn" 
                               download>
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif


            <!-- Activity Actions -->
            <div class="activity-actions">
                @if($submission && $submission->status === 'graded')
                    <a href="{{ route('student.tutor.activities', $activity->tutor) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Activities
                    </a>
                    @if($submission->feedback)
                        <button class="btn btn-success" onclick="showFeedback()">
                            <i class="fas fa-comment"></i> View Feedback
                        </button>
                    @endif
                @elseif($submission && $submission->status === 'submitted')
                    <a href="{{ route('student.tutor.activities', $activity->tutor) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Activities
                    </a>
                @else
                    <button type="button" class="btn btn-secondary" onclick="saveDraft()">
                        <i class="fas fa-save"></i> Save Draft
                    </button>
                    <button type="button" class="btn btn-primary" id="submit-activity-btn" onclick="submitActivity()">
                        <i class="fas fa-paper-plane"></i> Submit Activity
                    </button>
                @endif
            </div>
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
    
    <script>
        function saveDraft() {
            const form = document.getElementById('activity-form');
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Draft saved successfully!');
                } else {
                    alert('Error saving draft: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving draft. Please try again.');
            });
        }

        // Track submission state to prevent double submissions
        let isSubmitting = false;

        function submitActivity() {
            // Prevent double submission
            if (isSubmitting) {
                return;
            }

            const submitBtn = document.getElementById('submit-activity-btn');
            
            if (confirm('Are you sure you want to submit this activity? You won\'t be able to make changes after submission.')) {
                // Set submitting flag and disable button
                isSubmitting = true;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                
                const form = document.getElementById('activity-form');
                const formData = new FormData(form);
                
                fetch('{{ route("student.activities.submit", $activity) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message with score
                        const scoreMessage = data.score !== undefined 
                            ? `Activity submitted and automatically graded!\n\nYour Score: ${data.score} / ${data.total_points} (${Math.round((data.score / data.total_points) * 100)}%)`
                            : 'Activity submitted successfully!';
                        alert(scoreMessage);
                        // Small delay to ensure database update is committed, then reload
                        setTimeout(function() {
                            // Force reload with cache bypass
                            window.location.href = window.location.href + (window.location.href.indexOf('?') > -1 ? '&' : '?') + '_=' + new Date().getTime();
                        }, 500);
                    } else {
                        alert('Error submitting activity: ' + (data.message || 'Unknown error'));
                        // Re-enable button on error
                        isSubmitting = false;
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Activity';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error submitting activity. Please try again.');
                    // Re-enable button on error
                    isSubmitting = false;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Activity';
                });
            }
        }

        function showFeedback() {
            // Placeholder for showing feedback modal
            alert('Feedback: {{ $submission->feedback ?? "No feedback available." }}');
        }

    </script>
</body>
</html>
