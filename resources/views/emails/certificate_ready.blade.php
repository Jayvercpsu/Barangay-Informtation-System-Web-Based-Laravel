<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e40af; color: white; padding: 24px; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 24px; border: 1px solid #e5e7eb; }
        .alert { background: #f0fdf4; border: 1px solid #86efac; padding: 16px; border-radius: 6px; margin: 16px 0; }
        .footer { text-align: center; font-size: 12px; color: #6b7280; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0">Certificate Ready for Pickup</h2>
        <p style="margin:4px 0 0; opacity:0.8; font-size:14px">Community Service Desk</p>
    </div>
    <div class="content">
        <p>Dear <strong>{{ $request->resident->full_name }}</strong>,</p>
        <div class="alert">
            <p style="margin:0; font-weight:bold; color:#15803d;">✓ Your certificate is ready for pickup!</p>
        </div>
        <p><strong>Request Number:</strong> {{ $request->request_number }}<br>
        <strong>Certificate Type:</strong> {{ $request->certificate_label }}<br>
        <strong>Purpose:</strong> {{ $request->purpose }}</p>
        <p>Please visit the Barangay Hall during office hours to pick up your certificate. Bring a valid ID and this notification.</p>
        <p>Office Hours: Monday to Friday, 8:00 AM – 5:00 PM</p>
    </div>
    <div class="footer">
        <p>Community Service Desk — Barangay Information System</p>
    </div>
</body>
</html>