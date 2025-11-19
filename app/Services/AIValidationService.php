<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AIValidationService
{
    private $openaiApiKey;
    private $openaiBaseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
    }

    /**
     * Validate if the tutor's answer is relevant to the student's question
     * 
     * @param string $question The student's assignment question
     * @param string $answer The tutor's answer
     * @param string|null $subject Optional subject/course name
     * @return array ['is_relevant' => bool, 'confidence' => float, 'reason' => string]
     */
    public function validateAnswerRelevance(string $question, string $answer, ?string $subject = null): array
    {
        // Try OpenAI for validation (optional - only if API key is available)
        if (!empty($this->openaiApiKey)) {
            $openaiResult = $this->validateWithOpenAI($question, $answer, $subject);
            if ($openaiResult !== null) {
                return $openaiResult;
            }
        }

        // Use comprehensive local validation (works without any API)
        // Try fallback validation for simple math
        $fallbackResult = $this->fallbackValidation($question, $answer, $subject);
        if ($fallbackResult !== null) {
            Log::info('Using fallback validation for simple math question');
            return $fallbackResult;
        }

        // Try comprehensive heuristic validation (no API needed)
        $heuristicResult = $this->comprehensiveHeuristicValidation($question, $answer, $subject);
        if ($heuristicResult !== null) {
            Log::info('Using comprehensive heuristic validation');
            return $heuristicResult;
        }

        // If answer passed all basic checks, ACCEPT it
        // This allows the system to work without any API
        Log::info('Answer passed all local validation checks - accepting', [
            'question_preview' => substr($question, 0, 50),
            'answer_preview' => substr($answer, 0, 50),
            'openai_configured' => !empty($this->openaiApiKey)
        ]);
        
        return [
            'is_relevant' => true,
            'confidence' => 0.6,
            'reason' => 'Answer accepted. Your answer passed validation checks.'
        ];
    }


    /**
     * Validate using OpenAI API (paid, better quality)
     */
    private function validateWithOpenAI(string $question, string $answer, ?string $subject): ?array
    {
        try {
            $prompt = $this->buildValidationPrompt($question, $answer, $subject);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->openaiBaseUrl . '/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a strict educational content validator. Your job is to determine if a tutor\'s answer is RELEVANT, CORRECT, and APPROPRIATE for a student\'s question. You must REJECT answers that are wrong, trolling, or misleading. For math questions, verify the answer is mathematically correct. For factual questions, verify accuracy. Set is_relevant to FALSE if the answer is clearly wrong or inappropriate. IMPORTANT: In the "reason" field, DO NOT reveal the correct answer. Only state that the answer was rejected. Use this exact reason: "Answer rejected. Please take the answer more seriously or we will take immediate action." Respond ONLY with valid JSON in this exact format: {"is_relevant": true/false, "confidence": 0.0-1.0, "reason": "Answer rejected. Please take the answer more seriously or we will take immediate action."}'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.2,
                'max_tokens' => 250
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $content = $responseData['choices'][0]['message']['content'] ?? '';
                
                $validation = $this->parseAIResponse($content);
                
                if ($validation !== null) {
                    Log::info('OpenAI validation successful', [
                        'is_relevant' => $validation['is_relevant'],
                        'confidence' => $validation['confidence']
                    ]);
                    return $validation;
                }
            } else {
                $statusCode = $response->status();
                $errorBody = $response->body();
                
                Log::warning('OpenAI API request failed', [
                    'status' => $statusCode,
                    'body' => substr($errorBody, 0, 200)
                ]);
                
                // If quota exceeded (429) or authentication error (401), return null to try fallback
                if ($statusCode === 429 || $statusCode === 401) {
                    Log::info('OpenAI quota exceeded or authentication failed, trying fallback validation');
                    return null; // Will try fallback validation next
                }
            }
        } catch (Exception $e) {
            Log::error('OpenAI validation error', [
                'error' => $e->getMessage()
            ]);
        }

        return null; // Will try fallback validation
    }

    /**
     * Parse AI response and extract validation result
     */
    private function parseAIResponse(string $content): ?array
    {
        if (empty($content)) {
            return null;
        }

        // Parse JSON response
        $validation = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Try to extract JSON from markdown code blocks if present
            if (preg_match('/```json\s*(.*?)\s*```/s', $content, $matches)) {
                $validation = json_decode($matches[1], true);
            } elseif (preg_match('/```\s*(.*?)\s*```/s', $content, $matches)) {
                $validation = json_decode($matches[1], true);
            } else {
                // Fallback: try to find JSON object in the response
                if (preg_match('/\{[^}]+\}/', $content, $matches)) {
                    $validation = json_decode($matches[0], true);
                }
            }
        }

        if (!is_array($validation) || !isset($validation['is_relevant'])) {
            Log::warning('Invalid AI validation response format', ['content' => substr($content, 0, 200)]);
            return null;
        }

        return [
            'is_relevant' => (bool) $validation['is_relevant'],
            'confidence' => (float) ($validation['confidence'] ?? 0.5),
            'reason' => $validation['reason'] ?? 'Answer rejected. Please take the answer more seriously or we will take immediate action.'
        ];
    }

    /**
     * Fallback validation for simple cases when API is unavailable
     * This provides basic protection against obviously wrong answers
     */
    private function fallbackValidation(string $question, string $answer, ?string $subject): ?array
    {
        // Only use fallback for simple mathematical questions
        if (stripos($subject ?? '', 'math') !== false || 
            preg_match('/\d+\s*[+\-*\/]\s*\d+/', $question)) {
            
            // Extract numbers and operation from question
            if (preg_match('/(\d+)\s*([+\-*\/])\s*(\d+)/', $question, $matches)) {
                $num1 = (int)$matches[1];
                $operator = $matches[2];
                $num2 = (int)$matches[3];
                
                // Calculate correct answer
                $correctAnswer = match($operator) {
                    '+' => $num1 + $num2,
                    '-' => $num1 - $num2,
                    '*' => $num1 * $num2,
                    '/' => $num2 != 0 ? $num1 / $num2 : null,
                    default => null
                };
                
                if ($correctAnswer !== null) {
                    // Extract number from answer - try multiple patterns
                    $givenAnswer = null;
                    
                    // Try to find number in common formats: "The answer is 400", "400", "answer: 400", etc.
                    if (preg_match('/\b(\d+)\b/', $answer, $answerMatches)) {
                        $givenAnswer = (int)$answerMatches[1];
                    }
                    
                    if ($givenAnswer !== null) {
                        if ($givenAnswer != $correctAnswer) {
                            return [
                                'is_relevant' => false,
                                'confidence' => 0.95,
                                'reason' => 'Answer rejected. Please take the answer more seriously or we will take immediate action.'
                            ];
                        } else {
                            return [
                                'is_relevant' => true,
                                'confidence' => 0.9,
                                'reason' => 'Answer is mathematically correct.'
                            ];
                        }
                    }
                }
            }
        }
        
        // For non-math or complex questions, return null to try heuristic validation
        return null;
    }

    /**
     * Comprehensive heuristic validation - works without any API
     * Catches wrong answers, trolling, and validates common questions
     */
    private function comprehensiveHeuristicValidation(string $question, string $answer, ?string $subject = null): ?array
    {
        // First run basic checks
        $basicResult = $this->basicHeuristicValidation($question, $answer);
        if ($basicResult !== null) {
            return $basicResult;
        }
        
        // Then run enhanced knowledge-based validation
        return $this->knowledgeBasedValidation($question, $answer, $subject);
    }
    
    /**
     * Basic heuristic validation - catches trolling and obvious errors
     */
    private function basicHeuristicValidation(string $question, string $answer): ?array
    {
        $answerLower = strtolower(trim($answer));
        $questionLower = strtolower($question);
        
        // Reject very short answers ONLY if they match trolling patterns
        // Don't reject short but correct answers like "cell", "atom", "DNA", etc.
        if (strlen(trim($answer)) < 3) {
            return [
                'is_relevant' => false,
                'confidence' => 0.7,
                'reason' => 'Answer rejected. Please take the answer more seriously or we will take immediate action.'
            ];
        }
        
        // Reject common trolling patterns
        $trollPatterns = [
            '/^(idk|i don\'t know|dunno|no idea|maybe|probably|i think|idk|dont know)$/i',
            '/^(yes|no|maybe|probably|idk)$/i',
            '/^(lol|haha|lmao|rofl|wtf)$/i',
        ];
        
        foreach ($trollPatterns as $pattern) {
            if (preg_match($pattern, trim($answerLower))) {
                return [
                    'is_relevant' => false,
                    'confidence' => 0.8,
                    'reason' => 'Answer rejected. Please take the answer more seriously or we will take immediate action.'
                ];
            }
        }
        
        // For factual questions, check for some common wrong answers
        // This is a basic check - not comprehensive, but catches obvious errors
        
        // Check if question asks about largest planet
        if (preg_match('/largest\s+planet/i', $questionLower)) {
            $wrongAnswers = ['moon', 'earth', 'sun', 'mars', 'venus'];
            foreach ($wrongAnswers as $wrong) {
                if (stripos($answerLower, $wrong) !== false && stripos($answerLower, 'jupiter') === false) {
                    return [
                        'is_relevant' => false,
                        'confidence' => 0.85,
                        'reason' => 'Answer rejected. Please take the answer more seriously or we will take immediate action.'
                    ];
                }
            }
        }
        
        // Check if question asks about Red Planet
        if (preg_match('/red\s+planet/i', $questionLower)) {
            $wrongAnswers = ['moon', 'earth', 'sun', 'jupiter', 'venus', 'ocean', 'water', 'sea', 'mercury', 'saturn', 'neptune', 'uranus'];
            foreach ($wrongAnswers as $wrong) {
                if (stripos($answerLower, $wrong) !== false && stripos($answerLower, 'mars') === false) {
                    return [
                        'is_relevant' => false,
                        'confidence' => 0.9,
                        'reason' => 'Answer rejected. Please take the answer more seriously or we will take immediate action.'
                    ];
                }
            }
        }
        
        // Check if question asks about Blue Planet
        if (preg_match('/blue\s+planet/i', $questionLower)) {
            $wrongAnswers = ['moon', 'sun', 'mars', 'jupiter', 'venus', 'ocean', 'water', 'sea'];
            foreach ($wrongAnswers as $wrong) {
                if (stripos($answerLower, $wrong) !== false && stripos($answerLower, 'earth') === false) {
                    return [
                        'is_relevant' => false,
                        'confidence' => 0.9,
                        'reason' => 'Answer rejected. Please take the answer more seriously or we will take immediate action.'
                    ];
                }
            }
        }
        
        // Check if question asks about closest planet to the Sun
        if (preg_match('/closest.*(?:planet|to.*sun|sun)/i', $questionLower)) {
            // Mercury is the correct answer
            if (stripos($answerLower, 'mercury') !== false) {
                return [
                    'is_relevant' => true,
                    'confidence' => 0.95,
                    'reason' => 'Answer is correct.'
                ];
            }
            // Reject common wrong answers
            $wrongAnswers = ['venus', 'earth', 'mars', 'jupiter', 'saturn', 'uranus', 'neptune', 'pluto', 'moon', 'sun'];
            foreach ($wrongAnswers as $wrong) {
                if (stripos($answerLower, $wrong) !== false) {
                    return [
                        'is_relevant' => false,
                        'confidence' => 0.9,
                        'reason' => 'Answer rejected. Please take the answer more seriously or we will take immediate action.'
                    ];
                }
            }
        }
        
        // If answer seems reasonable (has some content, not obviously wrong), allow it
        // We'll let it through with a warning since we can't fully validate without API
        return null;
    }
    
    /**
     * Knowledge-based validation - validates common questions without API
     * Covers science, geography, history, and other common subjects
     */
    private function knowledgeBasedValidation(string $question, string $answer, ?string $subject = null): ?array
    {
        $answerLower = strtolower(trim($answer));
        $questionLower = strtolower($question);
        
        // Science - Biology
        if (preg_match('/smallest\s+unit\s+of\s+life|basic\s+unit\s+of\s+life|smallest\s+living/i', $questionLower)) {
            if (stripos($answerLower, 'cell') !== false) {
                return ['is_relevant' => true, 'confidence' => 0.95, 'reason' => 'Answer is correct.'];
            }
            $wrong = ['atom', 'molecule', 'tissue', 'organ', 'organism', 'dna', 'gene'];
            foreach ($wrong as $w) {
                if (stripos($answerLower, $w) !== false && stripos($answerLower, 'cell') === false) {
                    return ['is_relevant' => false, 'confidence' => 0.85, 'reason' => 'Answer rejected. Please take the answer more seriously or we will take immediate action.'];
                }
            }
        }
        
        // Science - Chemistry (atomic structure)
        if (preg_match('/proton|electron|neutron|atomic\s+structure/i', $questionLower)) {
            // Accept if answer contains relevant terms
            $validTerms = ['proton', 'electron', 'neutron', 'nucleus', 'atom', 'atomic'];
            $hasValidTerm = false;
            foreach ($validTerms as $term) {
                if (stripos($answerLower, $term) !== false) {
                    $hasValidTerm = true;
                    break;
                }
            }
            if (!$hasValidTerm && strlen(trim($answer)) > 10) {
                // If answer is long but doesn't mention any relevant terms, might be wrong
                return null; // Let it through, might be explaining in different words
            }
        }
        
        // Geography - Capital cities
        if (preg_match('/capital\s+of\s+(\w+)/i', $questionLower, $matches)) {
            $country = strtolower($matches[1] ?? '');
            $capitals = [
                'philippines' => 'manila',
                'japan' => 'tokyo',
                'china' => 'beijing',
                'usa' => 'washington',
                'united states' => 'washington',
                'uk' => 'london',
                'united kingdom' => 'london',
                'france' => 'paris',
                'germany' => 'berlin',
                'spain' => 'madrid',
                'italy' => 'rome',
                'australia' => 'canberra',
                'canada' => 'ottawa',
                'india' => 'new delhi',
                'brazil' => 'brasilia',
            ];
            if (isset($capitals[$country])) {
                if (stripos($answerLower, $capitals[$country]) !== false) {
                    return ['is_relevant' => true, 'confidence' => 0.95, 'reason' => 'Answer is correct.'];
                }
            }
        }
        
        // History - World War
        if (preg_match('/world\s+war\s+(one|1|two|2|ii|i)/i', $questionLower, $matches)) {
            $war = strtolower($matches[1] ?? '');
            if (stripos($war, 'one') !== false || stripos($war, '1') !== false || stripos($war, 'i') !== false) {
                // WW1 - accept if mentions relevant terms
                $validTerms = ['1914', '1918', 'allies', 'central powers', 'treaty', 'versailles'];
                foreach ($validTerms as $term) {
                    if (stripos($answerLower, $term) !== false) {
                        return null; // Seems relevant
                    }
                }
            }
        }
        
        // Math - Check for mathematical operations in answer
        if (preg_match('/calculate|solve|what\s+is\s+\d+|how\s+much|sum|difference|product|quotient/i', $questionLower)) {
            // If question is math-related, check if answer contains numbers or math terms
            if (preg_match('/\d+/', $answer) || preg_match('/\b(equals?|plus|minus|times|divided|multiply|add|subtract)\b/i', $answerLower)) {
                return null; // Seems like a math answer
            }
        }
        
        // General - Check answer length and relevance
        $answerLength = strlen(trim($answer));
        
        // Reject if answer is too short for complex questions
        if ($answerLength < 10 && preg_match('/explain|describe|why|how|what\s+is\s+the\s+reason/i', $questionLower)) {
            return [
                'is_relevant' => false,
                'confidence' => 0.7,
                'reason' => 'Answer rejected. Please provide a more complete answer.'
            ];
        }
        
        // Accept if answer has reasonable length and doesn't match troll patterns
        if ($answerLength >= 5 && $answerLength <= 5000) {
            return null; // Let it through - seems reasonable
        }
        
        return null;
    }

    /**
     * Build the validation prompt for OpenAI
     */
    private function buildValidationPrompt(string $question, string $answer, ?string $subject): string
    {
        $prompt = "Student's Question:\n{$question}\n\n";
        
        if ($subject) {
            $prompt .= "Subject/Course: {$subject}\n\n";
        }
        
        $prompt .= "Tutor's Answer:\n{$answer}\n\n";
        
        $prompt .= "Task: Determine if the tutor's answer is RELEVANT, CORRECT, and APPROPRIATE for the student's question. ";
        $prompt .= "You must REJECT the answer if ANY of the following are true:\n";
        $prompt .= "1. The answer is clearly WRONG or INCORRECT (e.g., answering '36' to 'What is 1+1?')\n";
        $prompt .= "2. The answer is trolling, spam, or intentionally misleading\n";
        $prompt .= "3. The answer is completely off-topic or doesn't address the question\n";
        $prompt .= "4. The answer is nonsensical or provides random/unrelated information\n";
        $prompt .= "5. The answer shows obvious lack of knowledge (e.g., 'I don't know' or 'maybe it's X' without explanation)\n\n";
        $prompt .= "For mathematical questions, verify the answer is mathematically correct.\n";
        $prompt .= "For factual questions, verify the answer is factually accurate.\n";
        $prompt .= "For conceptual questions, verify the answer demonstrates understanding.\n\n";
        $prompt .= "IMPORTANT: If the answer is clearly wrong (like '36' for '1+1'), you MUST set is_relevant to false with high confidence (≥0.8).\n\n";
        $prompt .= "CRITICAL: In the 'reason' field, DO NOT reveal the correct answer. DO NOT tell the tutor what the right answer is. Only use this exact message: 'Answer rejected. Please take the answer more seriously or we will take immediate action.'\n\n";
        $prompt .= "Respond with JSON only: {\"is_relevant\": true/false, \"confidence\": 0.0-1.0, \"reason\": \"Answer rejected. Please take the answer more seriously or we will take immediate action.\"}";
        
        return $prompt;
    }
}

