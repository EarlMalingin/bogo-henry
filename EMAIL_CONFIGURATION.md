# Email Configuration for Password Reset

To enable the password reset functionality that sends verification codes via email, you need to configure your email settings in the `.env` file.

## Step 1: Create .env file

If you don't have a `.env` file, copy the `.env.example` file and rename it to `.env`:

```bash
cp .env.example .env
```

## Step 2: Generate Application Key

Run the following command to generate your application key:

```bash
php artisan key:generate
```

## Step 3: Configure Email Settings

Add the following email configuration to your `.env` file:

### For Gmail (Recommended for testing):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="MentorHub"
```

### For Gmail App Password:

1. Enable 2-Factor Authentication on your Gmail account
2. Generate an App Password:
   - Go to Google Account settings
   - Security > 2-Step Verification > App passwords
   - Generate a new app password for "Mail"
   - Use this password in `MAIL_PASSWORD`

### For Other Email Providers:

#### Outlook/Hotmail:
```env
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

#### Yahoo:
```env
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

#### Custom SMTP Server:
```env
MAIL_HOST=your-smtp-server.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

## Step 4: Test Email Configuration

You can test your email configuration by running:

```bash
php artisan tinker
```

Then in the tinker console:

```php
Mail::raw('Test email from MentorHub', function($message) {
    $message->to('your-test-email@example.com')
            ->subject('Test Email');
});
```

## Step 5: Database Migration

Make sure you have run the database migrations:

```bash
php artisan migrate
```

## Troubleshooting

### Common Issues:

1. **"Failed to send reset code" error:**
   - Check your email credentials in `.env`
   - Ensure your email provider allows SMTP access
   - For Gmail, make sure you're using an App Password, not your regular password

2. **Emails going to spam:**
   - Add proper SPF and DKIM records to your domain
   - Use a reputable email service provider

3. **Connection timeout:**
   - Check your firewall settings
   - Verify the SMTP port is not blocked

### For Development/Testing:

If you want to see emails in logs instead of sending them (for development), change:

```env
MAIL_MAILER=log
```

This will log emails to `storage/logs/laravel.log` instead of sending them.

## Security Notes

- Never commit your `.env` file to version control
- Use environment-specific email configurations
- Consider using a dedicated email service like Mailgun, SendGrid, or AWS SES for production
- Regularly rotate your email passwords/app passwords 