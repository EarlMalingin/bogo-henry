# Hugging Face API Setup Guide

## Overview
The system now uses Hugging Face API as the **primary** validation method (free, 300 requests/hour), with OpenAI as a fallback.

## Setup Instructions

### 1. Get Hugging Face API Key (FREE)

1. Go to https://huggingface.co/
2. Sign up or log in to your account (it's free!)
3. Go to your profile → Settings → Access Tokens
4. Create a new token with "Read" permissions
5. Copy the token (starts with `hf_...`)

### 2. Add to Your .env File

Add this to your `.env` file:

```env
HUGGINGFACE_API_KEY=hf_your_token_here
```

Optional: You can also set a specific model (default is used if not set):
```env
HUGGINGFACE_MODEL=mistralai/Mistral-7B-Instruct-v0.2
```

### 3. Clear Configuration Cache

```bash
php artisan config:clear
```

## How It Works

### Priority Order:
1. **Hugging Face** (tries first - FREE, 300 requests/hour)
2. **OpenAI** (fallback if Hugging Face fails - requires credits)
3. **Fallback Validation** (simple math - always works)
4. **Heuristic Validation** (basic checks - always works)

### Rate Limits:
- **Hugging Face**: 300 requests per hour (resets every hour)
- If you exceed 300/hour, it automatically falls back to OpenAI
- If OpenAI also fails, uses local fallback validation

## Benefits

✅ **Free** - No payment required  
✅ **Reliable** - 300 requests/hour is usually enough  
✅ **Automatic Fallback** - Falls back to OpenAI if rate limited  
✅ **No Downtime** - Always has fallback validation  

## Troubleshooting

### Issue: Hugging Face not working
**Check:**
1. Is `HUGGINGFACE_API_KEY` set in `.env`?
2. Run `php artisan config:clear`
3. Check logs: `storage/logs/laravel.log` for "Hugging Face" entries

### Issue: Getting 503 errors
**Solution:** The model might be loading. The system automatically tries multiple models. Wait a moment and try again.

### Issue: Getting 429 errors (rate limited)
**Solution:** You've exceeded 300 requests/hour. The system will automatically fall back to OpenAI. Wait an hour for the limit to reset.

### Issue: Validation not working at all
**Check logs for:**
- "Hugging Face validation successful" - Working!
- "Hugging Face API request failed" - Check API key
- "All Hugging Face models failed" - Will use OpenAI or fallback

## Testing

To test if Hugging Face is working:

1. Make sure `HUGGINGFACE_API_KEY` is in `.env`
2. Clear config: `php artisan config:clear`
3. Try submitting a wrong answer (like "moon" for "largest planet")
4. Check logs: `storage/logs/laravel.log`
5. Look for "Hugging Face validation successful"

## Cost Comparison

| Service | Cost | Rate Limit |
|---------|------|------------|
| Hugging Face | FREE | 300/hour |
| OpenAI | ~$0.0001-0.0003 per request | No limit (but requires credits) |

## Notes

- Hugging Face is tried **first** (free option)
- If it fails, OpenAI is used automatically
- If both fail, local fallback validation is used
- System never breaks - always has a fallback!

