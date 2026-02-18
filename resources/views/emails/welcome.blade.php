<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e40af; color: white; padding: 24px; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 24px; border: 1px solid #e5e7eb; }
        .footer { text-align: center; font-size: 12px; color: #6b7280; margin-top: 20px; }
        .id-box { background: #eff6ff; border: 1px solid #bfdbfe; padding: 12px; border-radius: 6px; margin: 16px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0">Welcome to Community Service Desk</h2>
        <p style="margin:4px 0 0; opacity:0.8; font-size:14px">Barangay Information System</p>
    </div>
    <div class="content">
        <p>Dear <strong>{{ $resident->full_name }}</strong>,</p>
        <p>Your resident account has been successfully created. You can now access all barangay services online.</p>
        <div class="id-box">
            <p style="margin:0; font-size:12px; color:#6b7280;">Your Resident ID</p>
            <p style="margin:4px 0 0; font-size:24px; font-weight:bold; font-family:monospace; color:#1d4ed8;">{{ $resident->resident_id }}</p>
        </div>
        <p>With your account you can:</p>
        <ul>
            <li>Request barangay certificates online</li>
            <li>File and track complaints</li>
            <li>Receive SMS notifications</li>
            <li>Stay updated with community events</li>
        </ul>
        <p>If you have any concerns, please visit the Barangay Hall or contact us through this portal.</p>
        <p>Mabuhay!</p>
    </div>
    <div class="footer">
        <p>Community Service Desk — Barangay Information System</p>
        <p>This is an automated message. Please do not reply.</p>
    </div>
</body>
</html>