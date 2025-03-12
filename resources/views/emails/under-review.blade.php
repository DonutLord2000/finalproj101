<!DOCTYPE html>
<html>
<head>
    <title>Scholarship Application Under Review</title>
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
            background-color: #FF9900;
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
        <h1>Your Scholarship Application is Under Review</h1>
    </div>
    <div class="content">
        <p>Dear Applicant,</p>
        <p>We are writing to inform you that your application for the GRC-MLALAF Scholarship is now under review.</p>
        <p>Application Details:</p>
        <ul>
            <li>Email: {{ $application->email }}</li>
            <li>Status: Under Review</li>
            <li>Updated: {{ $application->updated_at->format('F d, Y') }}</li>
        </ul>
        <p>Our scholarship committee is carefully reviewing your application and supporting documents. We will notify you of the final decision once the review process is complete.</p>
        <p>Thank you for your patience.</p>
        <p>Regards,<br>GRC Scholarship Office</p>
    </div>
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html>