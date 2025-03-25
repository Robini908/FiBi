<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Demo Request</title>
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
            background-color: #1b5e20;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            color: #1b5e20;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Demo Request</h1>
    </div>
    
    <div class="content">
        <p>A new demo request has been submitted with the following details:</p>
        
        <div class="info-item">
            <span class="label">Name:</span> {{ $demoData['name'] }}
        </div>
        
        <div class="info-item">
            <span class="label">Email:</span> {{ $demoData['email'] }}
        </div>
        
        <div class="info-item">
            <span class="label">Phone:</span> {{ $demoData['phone'] }}
        </div>
        
        <div class="info-item">
            <span class="label">School/Institution:</span> {{ $demoData['school'] }}
        </div>
        
        <div class="info-item">
            <span class="label">Position:</span> {{ $demoData['position'] }}
        </div>
        
        <div class="info-item">
            <span class="label">Preferred Date:</span> {{ $demoData['preferredDate'] }}
        </div>
        
        @if(isset($demoData['message']) && !empty($demoData['message']))
        <div class="info-item">
            <span class="label">Additional Information:</span>
            <p>{{ $demoData['message'] }}</p>
        </div>
        @endif
        
        <p>Please schedule this demo as soon as possible and respond to the requester to confirm the details.</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from the Mbuku ERP system.</p>
    </div>
</body>
</html> 