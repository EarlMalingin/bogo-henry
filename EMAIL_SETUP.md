# Email Configuration for MentorHub

## Setting Up Email Verification

To enable email verification for user registration, you need to configure your email settings in the `.env` file.

### For Development (Using Gmail SMTP)

Add these settings to your `.env` file:

```env
# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="MentorHub"
```

### Gmail Setup Steps:

1. **Enable 2-Factor Authentication** on your Gmail account
2. **Generate App Password**:
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Generate a password for "Mail"
   - Use this password in `MAIL_PASSWORD`

### For Production (Using Other Providers)

#### Mailgun:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-secret
```

#### SendGrid:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

### Testing Email (Development)

For testing without sending real emails, use:

```env
MAIL_MAILER=log
```

This will log emails to `storage/logs/laravel.log` instead of sending them.

### Verification Process:

1. User registers with email
2. System generates 6-digit verification code
3. Code is sent to user's email
4. User enters code on verification page
5. Account is activated after successful verification
6. Code expires after 15 minutes

### Troubleshooting:

- **"Connection could not be established"**: Check your SMTP settings
- **"Authentication failed"**: Verify your email credentials
- **"Code not received"**: Check spam folder or try resend
- **"Code expired"**: Request a new verification code

### Security Features:

- ✅ 6-digit alphanumeric codes
- ✅ 15-minute expiration
- ✅ One-time use codes
- ✅ Rate limiting on resend
- ✅ Secure email templates
