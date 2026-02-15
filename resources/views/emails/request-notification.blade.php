<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['subject'] ?? 'Notification Alert' }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <div style="text-align: center; border-bottom: 2px solid #3366ff; padding-bottom: 20px; margin-bottom: 20px;">
            <h2 style="color: #3366ff; margin: 0;">{{ $data['subject'] ?? 'New Request Update' }}</h2>
        </div>

        <p>Dear Active User,</p>

        <p>We would like to inform you that a new activity has been recorded on the portal:</p>

        <div style="background: #f9f9f9; padding: 15px; border-radius: 4px; border-left: 4px solid #3366ff; margin: 20px 0;">
            <p style="margin: 0;"><strong>Type:</strong> {{ $data['type'] }}</p>
            <p style="margin: 5px 0 0;"><strong>Requested By:</strong> {{ $data['user_name'] }}</p>
            <p style="margin: 5px 0 0;"><strong>Details:</strong> {{ $data['details'] }}</p>
            <p style="margin: 5px 0 0;"><strong>Date:</strong> {{ date('d M Y, h:i A') }}</p>
        </div>

        <p>You can view the details by logging into the portal:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/') }}" style="background-color: #3366ff; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Login to Portal</a>
        </div>

        <p>Regards,<br><strong>EXAM360® (INDIA)</strong><br>Portal Notification System</p>
        
        <div style="border-top: 1px solid #eeeeee; padding-top: 20px; margin-top: 40px; font-size: 12px; color: #888; text-align: center;">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
