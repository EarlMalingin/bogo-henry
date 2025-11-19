# AI Validation Troubleshooting Guide

## Issue: Wrong Answers Are Being Accepted

If you're seeing incorrect answers like "36" for "1+1" being accepted, follow these steps:

### Step 1: Verify OpenAI API Key is Configured

1. Check your `.env` file has:
   ```env
   OPENAI_API_KEY=sk-...
   ```

2. Clear config cache:
   ```bash
   php artisan config:clear
   ```

3. Verify the key is loaded:
   ```bash
   php artisan tinker
   ```
   Then run:
   ```php
   config('services.openai.api_key')
   ```
   If it returns `null`, the API key is not configured.

### Step 2: Check Logs

Check `storage/logs/laravel.log` for:
- `"OpenAI API key not configured"` - means API key is missing
- `"AI validation result"` - shows validation attempts and results
- `"OpenAI API request failed"` - means API call failed

### Step 3: Test the Validation

To test if validation is working, try submitting an obviously wrong answer:
- Question: "What is 1 + 1?"
- Answer: "36"

This should be rejected. If it's not:
1. Check if API key is set
2. Check logs for errors
3. Verify the validation code is being called (check logs for "AI validation result")

### Step 4: Manual Test

You can test the AI service directly:

```bash
php artisan tinker
```

```php
use App\Services\AIValidationService;

$service = new AIValidationService();
$result = $service->validateAnswerRelevance(
    "What is 1 + 1?",
    "36",
    "Mathematics"
);

var_dump($result);
```

Expected output:
```php
[
    "is_relevant" => false,
    "confidence" => 0.9,  // High confidence it's wrong
    "reason" => "The answer '36' is mathematically incorrect for '1+1'"
]
```

### Common Issues

#### Issue: "AI validation is not configured"
**Solution:** Add `OPENAI_API_KEY` to your `.env` file and run `php artisan config:clear`

#### Issue: "AI validation service temporarily unavailable"
**Solution:** 
- Check your OpenAI account has credits
- Verify internet connection
- Check API key is valid

#### Issue: Validation passes but answer is still wrong
**Solution:**
- Check logs to see what confidence score was returned
- The threshold is set to 0.6 (60% confidence)
- If confidence is below 0.6, the answer will be accepted
- You can lower the threshold in `TutorAssignmentController.php` line 100

#### Issue: No logs appearing
**Solution:**
- Validation might not be running
- Check if the code is being executed
- Verify the controller method is being called

### Current Settings

- **Confidence Threshold:** 0.6 (60%)
- **Model:** gpt-3.5-turbo
- **Temperature:** 0.2 (lower = more strict)
- **Max Tokens:** 250

### Adjusting Sensitivity

To make validation stricter (reject more answers):
- Lower confidence threshold: Change `0.6` to `0.5` in `TutorAssignmentController.php` line 100

To make validation more lenient (accept more answers):
- Raise confidence threshold: Change `0.6` to `0.7` or `0.8`

