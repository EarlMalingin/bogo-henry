# PayMongo Webhook Setup Guide

## Step 1: Configure Your Webhook URL

In the PayMongo dashboard webhook creation form, use these settings:

### Endpoint URL
```
https://yourdomain.com/webhooks/paymongo
```

**For local development:**
```
https://your-ngrok-url.ngrok.io/webhooks/paymongo
```

### Events to Select
Check these events for your wallet system:

#### Required Events:
- ✅ `source.chargeable` - When a payment source is ready to be charged
- ✅ `payment.paid` - When a payment is successfully completed
- ✅ `payment.failed` - When a payment fails

#### Optional Events (for advanced features):
- `payment.refunded` - If you plan to add refund functionality
- `payment.refund.updated` - For refund status updates

## Step 2: Set Up Local Development with ngrok

For local testing, you'll need to expose your local server to the internet:

### Install ngrok
1. Download ngrok from [https://ngrok.com/](https://ngrok.com/)
2. Extract and add to your PATH

### Start ngrok
```bash
ngrok http 8000
```

This will give you a public URL like: `https://abc123.ngrok.io`

### Update Your Webhook URL
Use the ngrok URL in PayMongo dashboard:
```
https://abc123.ngrok.io/webhooks/paymongo
```

## Step 3: Configure Webhook Secret

1. **Get Webhook Secret from PayMongo**
   - After creating the webhook, PayMongo will provide a webhook secret
   - It looks like: `whsec_1234567890abcdef...`

2. **Add to Your .env File**
   ```env
   PAYMONGO_WEBHOOK_SECRET=whsec_your_webhook_secret_here
   ```

3. **Clear Config Cache**
   ```bash
   php artisan config:clear
   ```

## Step 4: Test Your Webhook

### Test the Endpoint
Visit: `https://yourdomain.com/webhooks/test`

You should see:
```json
{
    "status": "success",
    "message": "Webhook endpoint is working",
    "timestamp": "2025-10-04T06:45:00.000000Z"
}
```

### Test with PayMongo
1. Create a test payment in your app
2. Check PayMongo dashboard for webhook delivery status
3. Check your Laravel logs for webhook events

## Step 5: Monitor Webhook Events

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

### Webhook Event Logs
Look for these log entries:
- `PayMongo webhook received`
- `Payment paid processed successfully`
- `Payment failed processed`

## Step 6: Production Deployment

### Update Webhook URL
Change from ngrok URL to your production domain:
```
https://yourdomain.com/webhooks/paymongo
```

### Use Production Keys
Update your `.env` file with live keys:
```env
PAYMONGO_PUBLIC_KEY=pk_live_your_live_public_key
PAYMONGO_SECRET_KEY=sk_live_your_live_secret_key
PAYMONGO_WEBHOOK_SECRET=whsec_your_production_webhook_secret
```

## Troubleshooting

### Common Issues

1. **Webhook Not Receiving Events**
   - Check if your server is accessible from the internet
   - Verify the webhook URL is correct
   - Check PayMongo dashboard for delivery status

2. **Invalid Signature Error**
   - Verify webhook secret is correct
   - Check if webhook secret is properly set in .env
   - Clear config cache: `php artisan config:clear`

3. **Transaction Not Found**
   - Check if payment intent ID matches
   - Verify transaction exists in database
   - Check webhook payload structure

### Debug Commands

```bash
# Check webhook endpoint
curl -X GET https://yourdomain.com/webhooks/test

# Check webhook with POST
curl -X POST https://yourdomain.com/webhooks/paymongo \
  -H "Content-Type: application/json" \
  -d '{"test": "data"}'

# View recent logs
tail -n 50 storage/logs/laravel.log
```

## Security Notes

- Webhook endpoints are public and don't require authentication
- PayMongo signature verification ensures requests are legitimate
- Always use HTTPS in production
- Monitor webhook logs for suspicious activity
- Rotate webhook secrets periodically
