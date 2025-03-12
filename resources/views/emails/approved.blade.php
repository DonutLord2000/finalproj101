<!DOCTYPE html>
<html>
<head>
    <title>Scholarship Application Approved</title>
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
            background-color: #28a745;
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
        <h1>Congratulations! Your Scholarship Application is Approved</h1>
    </div>
    <div class="content">
        <p>Dear Applicant,</p>
        <p>We are pleased to inform you that your application for the GRC-MLALAF Scholarship has been approved.</p>
        <p>Application Details:</p>
        <ul>
            <li>Email: {{ $application->email }}</li>
            <li>Status: Approved</li>
            <li>Approved Date: {{ $application->updated_at->format('F d, Y') }}</li>
        </ul>
        @if($application->admin_notes)
        <p>Additional Notes: {{ $application->admin_notes }}</p>
        @endif
        <p>Please visit the Scholarship Office at your earliest convenience to complete the necessary paperwork.</p>
        <p>Congratulations once again!</p>
        <p>Regards,<br>GRC Scholarship Office</p>
    </div>
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
    </div>
</body>
</html>