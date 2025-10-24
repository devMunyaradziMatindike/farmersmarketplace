<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - Musika Wedu</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #059669;
            margin-bottom: 10px;
        }
        .tagline {
            color: #6b7280;
            font-size: 16px;
        }
        .content {
            margin-bottom: 30px;
        }
        .welcome {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 20px;
        }
        .seller-info {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .seller-info h3 {
            color: #166534;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .seller-info p {
            color: #15803d;
            margin: 0;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #059669;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
        }
        .button:hover {
            background: #047857;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .social {
            margin: 20px 0;
        }
        .social a {
            color: #059669;
            text-decoration: none;
            margin: 0 10px;
        }
        .unsubscribe {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🌾 MUSIKA WEDU</div>
            <div class="tagline">Zimbabwe's Premier Farmers Marketplace</div>
        </div>

        <div class="content">
            <div class="welcome">
                @if($user->isSeller())
                    Welcome to Musika Wedu, {{ $user->name }}! 🌾
                @else
                    Welcome to Musika Wedu, {{ $user->name }}! 🛒
                @endif
            </div>

            <div class="message">
                Thank you for joining Musika Wedu! To complete your registration and start using your account, please verify your email address by clicking the button below.
            </div>

            @if($user->isSeller())
                <div class="seller-info">
                    <h3>🌾 Seller Benefits Await You!</h3>
                    <p>Once verified, you'll be able to list your products, manage your inventory, connect with buyers across Zimbabwe, and grow your farming business with our platform.</p>
                </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">
                    @if($user->isSeller())
                        Verify Email & Start Selling
                    @else
                        Verify Email Address
                    @endif
                </a>
            </div>

            <div class="message">
                If the button doesn't work, you can copy and paste this link into your browser:
                <br>
                <a href="{{ $verificationUrl }}" style="color: #059669; word-break: break-all;">{{ $verificationUrl }}</a>
            </div>

            <div class="message">
                <strong>Important:</strong> This verification link will expire in 60 minutes for security reasons. If you need a new link, you can request one from the verification page.
            </div>
        </div>

        <div class="footer">
            <div class="social">
                <a href="#">Website</a> |
                <a href="#">Support</a> |
                <a href="#">Privacy Policy</a>
            </div>
            
            <p>
                <strong>Musika Wedu</strong><br>
                Connecting Farmers Across Zimbabwe<br>
                📧 support@farmersmarket.zw | 📱 +263 77 123 4567
            </p>
            
            <p style="font-size: 12px; color: #6b7280; margin-top: 15px;">
                <strong>Musika Wedu</strong> is a product of <strong>Nesso Systems (Pvt) Ltd</strong><br>
                Farm 42 Coburn Estate, Chegutu, Zimbabwe
            </p>

            <div class="unsubscribe">
                If you didn't create an account with Musika Wedu, please ignore this email.
            </div>
        </div>
    </div>
</body>
</html>
