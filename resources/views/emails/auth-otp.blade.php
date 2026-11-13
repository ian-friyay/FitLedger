<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FitLedger Security Verification</title>
</head>
<body style="background-color: #0a0c10; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #f1f5f9; margin: 0; padding: 30px 15px;">
    <div style="max-width: 520px; margin: 0 auto; background-color: #12151b; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.1); padding: 32px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);">
        
        <!-- Header / Logo -->
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="display: inline-block; font-size: 24px; font-weight: 900; letter-spacing: 0.05em; text-transform: uppercase; color: #ffffff;">
                FIT<span style="color: #FF7A00;">LEDGER</span>
            </div>
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; margin-top: 4px;">
                Security Verification
            </div>
        </div>

        <!-- Body -->
        <div style="text-align: center;">
            <h2 style="color: #ffffff; font-size: 20px; font-weight: 700; margin: 0 0 8px 0;">
                Hello, {{ $userName }}!
            </h2>
            <p style="color: #94a3b8; font-size: 14px; line-height: 1.5; margin: 0 0 24px 0;">
                Please use the following single-use verification code to complete your {{ strtolower($actionType) }}:
            </p>

            <!-- OTP Box -->
            <div style="background-color: #0a0c10; border: 1px solid rgba(255, 122, 0, 0.4); border-radius: 12px; padding: 18px 24px; margin-bottom: 24px; display: inline-block;">
                <span style="font-family: 'Courier New', Courier, monospace; font-size: 34px; font-weight: 900; letter-spacing: 0.25em; color: #FF7A00;">
                    {{ $otpCode }}
                </span>
            </div>

            <p style="color: #64748b; font-size: 12px; line-height: 1.5; margin: 0 0 20px 0;">
                This code will expire in <strong>10 minutes</strong>. Never share this code with anyone.
            </p>
        </div>

        <!-- Footer -->
        <div style="border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 16px; margin-top: 24px; text-align: center; font-size: 11px; color: #475569;">
            FitLedger Gym Management OS &bull; Secure Authentication Engine<br>
            If you did not request this verification, please secure your account immediately.
        </div>

    </div>
</body>
</html>
