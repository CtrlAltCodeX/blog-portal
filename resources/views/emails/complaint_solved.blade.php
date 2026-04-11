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
            background: #36b9cc;
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

        .success-box {
            background: #f0f9fa;
            border: 1px solid #36b9cc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #36b9cc;
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
            <h2>Case Resolution: {{ $statusLabel }}</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>This is to notify you that the complaint Ticket ID: <strong>{{ $complaint->complaint_id }}</strong> has
                been resolved.</p>

            <div class="success-box">
                <h3 style="margin-top: 0; color: #36b9cc;">Resolution Type: {{ $statusLabel }}</h3>
                <p>The case has been marked as <strong>{{ $statusLabel }}</strong> by the administration.</p>
            </div>

            <p><strong>Complaint Summary:</strong><br>
                {{ $complaint->title }}</p>

            <div style="text-align: center;">
                <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn">View Final Details</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>