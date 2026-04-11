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
            background: #f6c23e;
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

        .reminder-box {
            background: #fff9e6;
            border-left: 4px solid #f6c23e;
            padding: 15px;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #f6c23e;
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Daily Reminder: Open Complaint</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>This is a daily reminder for the following open complaint that requires attention:</p>

            <div class="reminder-box">
                <p><strong>Ticket ID:</strong> {{ $complaint->complaint_id }}<br>
                    <strong>Title:</strong> {{ $complaint->title }}<br>
                    <strong>Created Date:</strong> {{ $complaint->created_at->format('d M, Y') }}<br>
                    <strong>Days Open:</strong> {{ $complaint->created_at->diffInDays(now()) }} days
                </p>
            </div>

            <p>Please check the progress and provide a resolution as soon as possible.</p>

            <div style="text-align: center;">
                <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn">View Complaint</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>