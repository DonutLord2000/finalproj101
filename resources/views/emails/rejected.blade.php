<!DOCTYPE html>
<html>
<head>
    <title>Scholarship Application Update</title>
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
            background-color: #dc3545;
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
        <h1>Update on Your Scholarship Application</h1>
    </div>
    <div class="content">
        <p>Dear Applicant,</p>
        <p>We regret to inform you that your application for the GRC-MLALAF Scholarship has not been approved at this time.</p>
        <p>Application Details:</p>
        <ul>
            <li>Email: {{ $application->email }}</li>
            <li>Status: Rejected</li>
            <li>Decision Date: {{ $application->updated_at->format('F d, Y') }}</li>
        </ul>
        @if($application->admin_notes)
        <p>Feedback: {{ $application->admin_notes }}</p>
        @endif
        <p>We encourage you to apply again in the future or explore other scholarship opportunities.</p>
        <p>Thank you for your interest in the GRC-MLALAF Scholarship Program.</p>
        <p>Regards,<br>GRC Scholarship Office</p>
    </div>
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html>