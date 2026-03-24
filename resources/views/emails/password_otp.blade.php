<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
</head>
<body style="margin:0;padding:24px;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width:560px;background:#ffffff;border:1px solid #e5e7eb;border-radius:14px;padding:24px;">
                    <tr>
                        <td style="font-size:20px;font-weight:700;color:#111827;">Password Reset OTP</td>
                    </tr>
                    <tr>
                        <td style="padding-top:10px;font-size:14px;line-height:1.6;color:#4b5563;">
                            Use this one-time password (OTP) to reset your account password. This code is valid for 10 minutes.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:18px;">
                            <div style="display:inline-block;padding:12px 18px;background:#eef2ff;border:1px dashed #6366f1;border-radius:10px;font-size:28px;letter-spacing:6px;font-weight:700;color:#1f2937;">
                                {{ $otp }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:16px;font-size:13px;line-height:1.6;color:#6b7280;">
                            If you did not request this, you can ignore this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
