<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: #1cc88a;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 30px;
        }

        .footer {
            background: #f1f1f1;
            color: #777;
            padding: 15px;
            text-align: center;
            font-size: 12px;
        }

        .reply-box {
            background: #f8f9fc;
            border-left: 4px solid #1cc88a;
            padding: 15px;
            margin-top: 20px;
            font-style: italic;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #1cc88a;
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>New Reply Submitted</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>A new reply has been submitted for Ticket ID: <strong>{{ $complaint->complaint_id }}</strong>.</p>

            <p><strong>Reply Message:</strong></p>
            <div class="reply-box">
                {{ $reply->message }}
            </div>

            <p><strong>Current Status:</strong> {{ ucfirst($complaint->status) }}</p>

            <div style="text-align: center;">
                @if(Request::is('admin/*'))
                <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn">View Conversation</a>
                @else
                <a href="{{ route('public.complaints.show', $complaint->id) }}" class="btn">View Conversation</a>
                @endif
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>