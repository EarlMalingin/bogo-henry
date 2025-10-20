# Email Configuration Fix for MentorHub

## The Problem
Your emails are not being sent because `MAIL_MAILER=log` in your `.env` file. This setting logs emails to files instead of sending them.

## The Solution
Update your `.env` file with the correct SMTP settings for Gmail.

## Step 1: Update Your .env File

Replace your current email settings in `.env` with these:

```env
# Email Configuration for Gmail SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=mentorhub.website@gmail.com
MAIL_PASSWORD=mhrzhdomxdpefjiy
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="mentorhub.website@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Step 2: Clear Configuration Cache

After updating the `.env` file, run these commands:

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 3: Test Email Sending

You can test if emails are working by:

1. **Try registering a new account** - You should receive a verification email
2. **Try forgot password** - You should receive a password reset code
3. **Check your spam folder** - Sometimes emails go there initially

## Alternative: Use Log Driver for Testing

If you want to see what emails would be sent without actually sending them, you can:

1. Keep `MAIL_MAILER=log`
2. Check the log files at `storage/logs/laravel.log`
3. Look for the email content in the logs

## Troubleshooting

### If emails still don't work:

1. **Check Gmail App Password**: Make sure `mhrzhdomxdpefjiy` is a valid app password
2. **Enable 2-Factor Authentication**: Required for app passwords
3. **Check Gmail Settings**: Allow less secure apps (if not using app password)
4. **Check Firewall**: Port 587 might be blocked

### Gmail App Password Setup:

1. Go to Google Account settings
2. Security → 2-Step Verification → App passwords
3. Generate password for "Mail"
4. Use that password in `MAIL_PASSWORD`

## Quick Test

After making changes, try registering a new account. You should receive a verification email within a few seconds.

## Need Help?

If you're still having issues, check:
- Gmail account security settings
- App password validity
- Network connectivity
- Laravel logs for error messages
