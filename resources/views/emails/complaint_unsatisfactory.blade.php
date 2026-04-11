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
            background: #e74a3b;
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

        .warning-box {
            background: #fff1f0;
            border-left: 4px solid #e74a3b;
            padding: 15px;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #e74a3b;
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
            <h2>Response Un-Satisfactory</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>The user has marked the response for Ticket ID: <strong>{{ $complaint->complaint_id }}</strong> as
                unsatisfactory.</p>

            <div class="warning-box">
                <p><strong>User's Message:</strong><br>
                    {{ $reply->message }}</p>
            </div>

            <p>Please review the case again and provide a more suitable resolution.</p>

            <div style="text-align: center;">
                <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn">Review Complaint</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>