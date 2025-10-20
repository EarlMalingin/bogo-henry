# PayMongo Integration Setup Guide

## Environment Configuration

Add these variables to your `.env` file:

```env
# PayMongo Configuration
PAYMONGO_PUBLIC_KEY=pk_test_your_public_key_here
PAYMONGO_SECRET_KEY=sk_test_your_secret_key_here
PAYMONGO_WEBHOOK_SECRET=whsec_your_webhook_secret_here
```

## Getting PayMongo Credentials

1. **Sign up for PayMongo Account**
   - Go to [PayMongo Dashboard](https://dashboard.paymongo.com/)
   - Create an account and complete verification

2. **Get API Keys**
   - Navigate to API Keys section in dashboard
   - Copy your Public Key and Secret Key
   - For testing, use the test keys (pk_test_... and sk_test_...)

3. **Set up Webhooks (Optional)**
   - Go to Webhooks section in dashboard
   - Create a webhook pointing to your domain
   - Copy the webhook secret

## Features Implemented

### Wallet System
- ✅ Wallet model with balance tracking
- ✅ Transaction history
- ✅ Cash in via GCash QR code
- ✅ Cash out to GCash account
- ✅ Real-time balance display in dashboard

### PayMongo Integration
- ✅ Payment intent creation
- ✅ GCash source generation
- ✅ Payment status tracking
- ✅ Success/failure callbacks

### User Interface
- ✅ Modern wallet dashboard
- ✅ Cash in form with amount presets
- ✅ Cash out form with GCash account details
- ✅ Payment page with QR code display
- ✅ Transaction history with status indicators

## Usage

### For Students
- Access wallet via dashboard currency display
- Cash in: `/student/wallet/cash-in`
- Cash out: `/student/wallet/cash-out`
- View transactions: `/student/wallet`

### For Tutors
- Access wallet via dashboard currency display
- Cash in: `/tutor/wallet/cash-in`
- Cash out: `/tutor/wallet/cash-out`
- View transactions: `/tutor/wallet`

## Testing

1. Use PayMongo test keys for development
2. Test with small amounts first
3. Check transaction status in PayMongo dashboard
4. Verify webhook callbacks are working

## Production Deployment

1. Replace test keys with live keys
2. Update webhook URLs to production domain
3. Test thoroughly with real GCash accounts
4. Monitor transaction logs

## Security Notes

- Never commit API keys to version control
- Use environment variables for all sensitive data
- Implement proper error handling
- Log all transactions for audit purposes
- Validate all user inputs
