<!DOCTYPE html>
<html>
<head>
    <title>Contact Form Submission</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background-color: #1a8754;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>New Contact Form Submission</h2>
    </div>
    
    <div class="content">
        <div class="info-row">
            <span class="label">Name:</span> {{ $contactData['name'] }}
        </div>
        
        <div class="info-row">
            <span class="label">Email:</span> {{ $contactData['email'] }}
        </div>
        
        <div class="info-row">
            <span class="label">Phone:</span> {{ $contactData['phone'] }}
        </div>
        
        <div class="info-row">
            <span class="label">Subject:</span> {{ $contactData['subject'] }}
        </div>
        
        <div class="info-row">
            <span class="label">Message:</span>
            <p>{{ $contactData['message'] }}</p>
        </div>
    </div>
    
    <div class="footer">
        <p>This email was sent from the Mbuku ERP contact form at {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html> 