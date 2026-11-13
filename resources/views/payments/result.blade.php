<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment {{ $status === 'success' ? 'received' : 'cancelled' }} - FitLedger</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            background: #f4f6f5;
            color: #14231c;
        }
        .card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border-radius: 18px;
            padding: 36px 32px;
            box-shadow: 0 20px 50px rgba(20, 35, 28, .12);
            text-align: center;
        }
        .icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 20px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            font-size: 30px;
            font-weight: 700;
        }
        .success { background: #dcfce7; color: #15803d; }
        .cancelled { background: #fee2e2; color: #b91c1c; }
        h1 { margin: 0 0 10px; font-size: 24px; }
        p { margin: 0; color: #54645c; line-height: 1.6; }
        .ref {
            margin-top: 22px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #f1f5f2;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: 13px;
            color: #33473d;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <main class="card">
        <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 24px;">
            <img src="{{ asset('images/fitledger-logo.jpg') }}" alt="FitLedger Logo" 
                 style="height: 38px; width: auto; border-radius: 12px; object-fit: contain; background: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 4px 15px rgba(255, 122, 0, 0.25);">
            <div style="text-align: left; line-height: 1;">
                <span style="font-family: -apple-system, BlinkMacSystemFont, sans-serif; font-size: 20px; font-weight: 900; letter-spacing: 0.05em; text-transform: uppercase; color: #0a0c10; display: block;">
                    FIT<span style="color: #FF7A00;">LEDGER</span>
                </span>
                <span style="font-size: 9px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #FF7A00; display: block; margin-top: 3px;">
                    Payment Confirmation
                </span>
            </div>
        </div>
        @if ($status === 'success')
            <div class="icon success">&#10003;</div>
            <h1>Payment received</h1>
            <p>Thank you. Your GCash payment is being confirmed. You may close this window and return to the gym.</p>
        @else
            <div class="icon cancelled">&#10005;</div>
            <h1>Payment cancelled</h1>
            <p>No payment was made. You may close this window and ask the front desk for a new GCash checkout link.</p>
        @endif

        @if (request('ref'))
            <div class="ref">Reference: {{ request('ref') }}</div>
        @endif
    </main>
</body>
</html>
