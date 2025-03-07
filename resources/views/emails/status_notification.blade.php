<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
        }
        .bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
          
        }
        .center-text {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="">
    <h2>{{ strtoupper($status) }}</h2>
    
    <p class="content">
        Dear Freelancer's,<br><br>
        {{ $body }}
    </p>

    <p class="content">
        If you believe this action was taken in error or require further clarification, please feel free to contact our Support Team at
        <a href="https://support.exam360.in/" target="_blank">https://support.exam360.in/.</a>.
    </p>

    <p class="content">
        Additionally, all your previous work reports up to date will be reviewed and investigated—either manually or by our AI system—as a precautionary measure. If we identify any faults or violations in your all-time work history, we may freeze your balance amount until we receive a satisfactory explanation regarding the activities that may have affected our customers or office work culture.
    </p>

    <p >We appreciate your cooperation in this matter.</p>
    <a href="https://support.exam360.in" style="text-align: center;">
        CONTACT SUPPORT TEAM
    </a>

    <div class="footer">
        <p>Best regards,</p>
        <p>Project Zero Team (User’s Account Branch)</p>
        <p>EXAM360® (INDIA)</p>
    </div>
</div>

</body>
</html>



