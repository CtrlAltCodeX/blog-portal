<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6fb; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #667eea, #764ba2); color: #ffffff; padding: 30px; text-align: center; }
        .content { padding: 40px; text-align: center; color: #333333; }
        .otp-box { background: #f8f9fa; border: 2px dashed #667eea; border-radius: 10px; padding: 20px; display: inline-block; margin: 20px 0; }
        .otp-code { font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #1e3c72; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #777777; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Verification Required</h2>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>You are receiving this email because a complaint request was initiated on our portal using your email address.</p>
            <p>Please use the following One-Time Password (OTP) to verify your identity:</p>
            
            <div class="otp-box">
                <span class="otp-code">{{ $otp }}</span>
            </div>
            
            <p>This OTP is valid for 10 minutes. If you did not request this, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
