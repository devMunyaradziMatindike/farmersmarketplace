# 📧 Email Configuration Guide for Musika Wedu

This guide covers setting up email verification for seller registration with support for all major email providers.

## 🚀 Quick Setup

### 1. Gmail Configuration (Recommended)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@farmersmarket.zw
MAIL_FROM_NAME="Musika Wedu"
```

**Gmail Setup Steps:**
1. Enable 2-Factor Authentication on your Gmail account
2. Generate an App Password: Google Account → Security → App passwords
3. Use the App Password (not your regular password) in `MAIL_PASSWORD`

### 2. Outlook/Hotmail Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@farmersmarket.zw
MAIL_FROM_NAME="Musika Wedu"
```

### 3. Yahoo Mail Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yahoo.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@farmersmarket.zw
MAIL_FROM_NAME="Musika Wedu"
```

**Yahoo Setup Steps:**
1. Enable 2-Factor Authentication
2. Generate App Password: Yahoo Account → Security → Generate app password
3. Use the App Password in `MAIL_PASSWORD`

### 4. Custom SMTP Server

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@farmersmarket.zw
MAIL_FROM_NAME="Musika Wedu"
```

## 🔧 Advanced Configuration

### Using Mailgun (Professional)

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-secret
MAIL_FROM_ADDRESS=noreply@farmersmarket.zw
MAIL_FROM_NAME="Musika Wedu"
```

### Using SendGrid

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@farmersmarket.zw
MAIL_FROM_NAME="Musika Wedu"
```

## 🧪 Testing Email Configuration

### 1. Test with Tinker
```bash
php artisan tinker
```

```php
use Illuminate\Support\Facades\Mail;
use App\Models\User;

// Test email sending
Mail::raw('Test email from Musika Wedu', function ($message) {
    $message->to('test@example.com')
            ->subject('Test Email');
});

// Test verification email
$user = User::find(1);
$user->sendEmailVerificationNotification();
```

### 2. Test Registration Flow
1. Register as a seller
2. Check email inbox
3. Click verification link
4. Verify redirect to login page

## 📧 Email Templates

The system uses Laravel's built-in email verification templates, but you can customize them by publishing the views:

```bash
php artisan vendor:publish --tag=laravel-mail
```

## 🔒 Security Considerations

1. **Use App Passwords**: Never use your main email password
2. **Environment Variables**: Keep email credentials in `.env` file
3. **HTTPS**: Ensure your application uses HTTPS in production
4. **Rate Limiting**: Email verification is rate-limited to prevent spam

## 🚨 Troubleshooting

### Common Issues:

1. **"Authentication failed"**
   - Check username/password
   - Ensure 2FA is enabled and using App Password

2. **"Connection refused"**
   - Check SMTP host and port
   - Verify firewall settings

3. **"Emails not received"**
   - Check spam folder
   - Verify FROM address is not blocked
   - Test with different email providers

### Debug Mode:
```env
MAIL_MAILER=log
```
This will log emails to `storage/logs/laravel.log` instead of sending them.

## 📱 Mobile Email Apps

The verification emails work with all email clients:
- **Gmail App**: ✅ Supported
- **Outlook App**: ✅ Supported  
- **Yahoo Mail App**: ✅ Supported
- **Apple Mail**: ✅ Supported
- **Android Email**: ✅ Supported

## 🌍 International Support

The system supports international email addresses and domains:
- `.com`, `.org`, `.net` domains
- Country-specific domains (`.co.zw`, `.co.uk`, etc.)
- International characters in email addresses

## 📊 Monitoring

Monitor email delivery in production:
- Check Laravel logs for email errors
- Monitor bounce rates
- Track verification completion rates

## 🔄 Fallback Options

If primary email fails, users can:
1. Resend verification email
2. Contact support
3. Use alternative registration methods (phone/Google OAuth)
