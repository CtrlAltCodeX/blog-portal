<!DOCTYPE html>
<html>

<head>
    <style>
        /* Basic email styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .email-header {
            text-align: center;
            font-size: 24px;
            color: #333;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #e0e0e0;
        }

        th {
            background-color: #f0f0f0;
            color: #333;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f7fa;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">Userwise Listing Reports</div>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Total Listing (Create/Edit)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userListingsCount as $key => $user)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{ $user->counts->sum('create_count') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="text-align:justify"><strong>Best regards,</strong></p>
        <p style="text-align:justify">EXAM360® (INDIA)<br>Project Zero Team.</p>
        <div style="width:100%;text-align:center;font-family:sans-serif">
            <hr>
            <p style="font-size:11px;text-align:justify;text-justify:inter-word"><b>Note: </b>If you didn't request anything through <a href="https://support.exam360.in" rel="noreferrer noreferrer" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://support.exam360.in&amp;source=gmail&amp;ust=1729841505934000&amp;usg=AOvVaw3m2j0EqS8KJBsLHhM-aiqu">support.exam360.in</a> or this was not you, you don't need to do anything and no Actions are Required. Please do not reply to this email. Emails sent to this address will not be answered.</p>
        </div>
    </div>
</body>

</html>