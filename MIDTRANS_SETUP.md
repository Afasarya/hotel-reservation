# Midtrans Payment Gateway Setup

## 1. Midtrans Account Setup

1. Register at [Midtrans](https://midtrans.com/)
2. Go to Dashboard → Settings → Access Keys
3. Copy your **Server Key** and **Client Key** from Sandbox environment

## 2. Environment Configuration

Add these lines to your `.env` file:

```env
# Midtrans Configuration
MIDTRANS_SERVER_KEY=your_midtrans_server_key_here
MIDTRANS_CLIENT_KEY=your_midtrans_client_key_here
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

## 3. For Testing (Sandbox)

Use these test credentials:

**Server Key**: `SB-Mid-server-YOUR_SERVER_KEY`
**Client Key**: `SB-Mid-client-YOUR_CLIENT_KEY`

## 4. Test Credit Cards

For testing payments, use these test card numbers:

- **Visa**: 4811 1111 1111 1114
- **Mastercard**: 5264 2210 3887 4659
- **CVV**: 123
- **Expiry**: Any future date
- **OTP**: 112233

## 5. Webhook Configuration

In Midtrans Dashboard:
1. Go to Settings → Configuration
2. Set Payment Notification URL to: `https://yourdomain.com/api/payment/webhook`
3. Set Finish Redirect URL to: `https://yourdomain.com/payment/finish`
4. Set Unfinish Redirect URL to: `https://yourdomain.com/payment/unfinish`
5. Set Error Redirect URL to: `https://yourdomain.com/payment/error`

## 6. Production Setup

When ready for production:
1. Change `MIDTRANS_IS_PRODUCTION=true`
2. Use Production Server Key and Client Key
3. Update webhook URLs to production domain

## 7. Troubleshooting

If payment window doesn't appear:
1. Check if Midtrans keys are correctly set in `.env`
2. Verify internet connection
3. Check browser console for JavaScript errors
4. Ensure Midtrans Snap script is loaded

## 8. Testing the Integration

1. Create a booking
2. Go to payment page
3. Click "Pay Now" button
4. Midtrans payment window should appear
5. Use test card numbers above
6. Complete payment flow 