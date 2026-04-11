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
            background: #4e73df;
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

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }

        .badge-pending {
            background: #f6c23e;
            color: #fff;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .info-table th,
        .info-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .info-table th {
            color: #555;
            width: 35%;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #4e73df;
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
            <h2>New Complaint Raised</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>A new complaint has been registered in the system. Here are the details:</p>

            <table class="info-table">
                <tr>
                    <th>Ticket ID:</th>
                    <td><strong>{{ $complaint->complaint_id }}</strong></td>
                </tr>
                <tr>
                    <th>Title:</th>
                    <td>{{ $complaint->title }}</td>
                </tr>
                <tr>
                    <th>Department:</th>
                    <td>{{ $complaint->department->name }}</td>
                </tr>
                <tr>
                    <th>Issue Type:</th>
                    <td>{{ $complaint->issueType->name }}</td>
                </tr>
                <tr>
                    <th>Created By:</th>
                    <td>{{ $complaint->complaint_user->name ?? 'N/A' }} ({{ $complaint->complaint_user->email ?? '' }})
                    </td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td><span class="badge badge-pending">Pending</span></td>
                </tr>
            </table>

            <p><strong>Description:</strong><br>
                {{ $complaint->description }}</p>


        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>