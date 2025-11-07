<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Exam360</a>
    </div>
    <p style="font-size:1.1em">Hi,</p>
    <p>Use the following OTP to complete your Sign In procedures</p>
    <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">{{ $otp }}</h2>

    @if (isset($user))
    <h3 style="margin-top:20px;">User Registration Details:</h3>
    <table style="width:100%;border-collapse:collapse;">
      <tr><td><strong>Name:</strong></td><td>{{ $user['name'] ?? '-' }}</td></tr>
      <tr><td><strong>Email:</strong></td><td>{{ $user['email'] ?? '-' }}</td></tr>
      <tr><td><strong>Mobile:</strong></td><td>{{ $user['mobile'] ?? '-' }}</td></tr>
      <tr><td><strong>Account Type:</strong></td><td>{{ $user['account_type'] ?? '-' }}</td></tr>
      <tr><td><strong>Aadhaar No:</strong></td><td>{{ $user['aadhaar_no'] ?? '-' }}</td></tr>
      <tr><td><strong>Father Name:</strong></td><td>{{ $user['father_name'] ?? '-' }}</td></tr>
      <tr><td><strong>Mother Name:</strong></td><td>{{ $user['mother_name'] ?? '-' }}</td></tr>
      <tr><td><strong>State:</strong></td><td>{{ $user['state'] ?? '-' }}</td></tr>
      <tr><td><strong>Pincode:</strong></td><td>{{ $user['pincode'] ?? '-' }}</td></tr>
      <tr><td><strong>Full Address:</strong></td><td>{{ $user['full_address'] ?? '-' }}</td></tr>
    </table>
    @endif

    <p style="font-size:0.9em;">Regards,<br />Exam360</p>
    <hr style="border:none;border-top:1px solid #eee" />
  </div>
</div>