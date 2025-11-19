# AI Answer Validation Setup

## Overview
The system now includes AI-powered validation to ensure tutor answers are relevant to student questions. This prevents trolling, spam, and irrelevant answers from being posted.

## How It Works
- When a tutor submits an answer to a student's assignment, the system uses OpenAI's GPT model to validate if the answer is relevant
- The AI checks:
  1. Does the answer address the question asked?
  2. Is the answer relevant to the subject matter?
  3. Is the answer educational and helpful (not trolling, spam, or completely off-topic)?
  4. Does the answer provide meaningful content?
- Answers are only rejected if the AI is confident (≥70% confidence) that the answer is not relevant
- If the AI validation service fails or is unavailable, answers are accepted by default (fail-open approach)

## Setup Instructions

### 1. Get OpenAI API Key
1. Go to https://platform.openai.com/
2. Sign up or log in to your account
3. Navigate to API Keys section
4. Create a new API key
5. Copy the API key (you won't be able to see it again)

### 2. Configure Environment Variable
Add the following to your `.env` file:

```env
OPENAI_API_KEY=your_openai_api_key_here
```

### 3. Clear Configuration Cache
After adding the API key, clear Laravel's configuration cache:

```bash
php artisan config:clear
```

## How It Works in Practice

### When AI Validation Passes
- Tutor submits answer → AI validates → Answer is posted normally

### When AI Validation Fails
- Tutor submits irrelevant answer → AI detects it's not relevant (≥70% confidence) → Answer is rejected
- Tutor sees error message: "Your answer was rejected because it does not appear to be relevant to the student's question. Please provide a helpful, on-topic answer."
- The form data is preserved so the tutor can revise their answer

### When AI Service is Unavailable
- If OpenAI API is down or API key is missing → Answer is accepted (fail-open)
- Error is logged for monitoring
- System continues to function normally

## Cost Considerations
- OpenAI API charges per token used
- Each validation uses approximately 200-500 tokens
- Using GPT-3.5-turbo model (cost-effective option)
- Estimated cost: ~$0.0001-0.0003 per validation
- For 1000 validations: ~$0.10-0.30

## Monitoring
- All AI validation attempts are logged in `storage/logs/laravel.log`
- Look for entries with "AI validation" or "OpenAI API" keywords
- Monitor for API errors or rate limits

## Troubleshooting

### Issue: Answers are being rejected incorrectly
**Solution:** The confidence threshold is set to 70%. You can adjust this in `TutorAssignmentController.php` line 90:
```php
if (!$validation['is_relevant'] && $validation['confidence'] >= 0.7) {
    // Change 0.7 to a higher value (e.g., 0.8 or 0.9) for stricter validation
    // Or lower value (e.g., 0.6) for more lenient validation
}
```

### Issue: API key not working
**Solution:**
1. Verify the API key is correct in `.env`
2. Check if you have credits in your OpenAI account
3. Run `php artisan config:clear`
4. Check logs for specific error messages

### Issue: Validation is too slow
**Solution:**
- The API call has a 30-second timeout
- If consistently slow, consider upgrading OpenAI plan or using a faster model
- You can reduce timeout in `AIValidationService.php` line 48

## Disabling AI Validation
If you need to temporarily disable AI validation:
1. Remove or comment out the `OPENAI_API_KEY` in `.env`
2. Run `php artisan config:clear`
3. The system will accept all answers (fail-open behavior)

## Security Notes
- API key is stored in `.env` file (never commit to version control)
- API key is accessed via Laravel's config system
- All API calls are logged for audit purposes
- Fail-open approach ensures system availability even if AI service fails

