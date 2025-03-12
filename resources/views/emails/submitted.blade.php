<!DOCTYPE html>
<html>
<head>
    <title>Scholarship Application Received</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #003366;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        ul {
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Scholarship Application Received</h1>
    </div>
    <div class="content">
        <p>Dear Applicant,</p>
        <p>We have received your scholarship application. Your application is currently pending review.</p>
        <p>Application Details:</p>
        <ul>
            <li>Email: {{ $application->email }}</li>
            <li>Status: {{ ucfirst(str_replace('_', ' ', $application->status)) }}</li>
            <li>Submitted: {{ $application->created_at->format('F d, Y') }}</li>
        </ul>
        <p>We will notify you of any updates regarding your application.</p>
        <p>Thank you for applying to the GRC-MLALAF Scholarship Program.</p>
        <p>Regards,<br>GRC Scholarship Office</p>
    </div>
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html>