<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shop->name }}からのお知らせ</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            border-bottom: 3px solid #059669;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .shop-name {
            color: #059669;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        .message-content {
            color: #333;
            font-size: 16px;
            line-height: 1.8;
            white-space: pre-wrap;
            margin-bottom: 30px;
        }
        .footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
        .footer-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1 class="shop-name">{{ $shop->name }}</h1>
        </div>

        <div class="message-content">
            {{ $emailMessage }}
        </div>

        <div class="footer">
            <p class="footer-title">このメールについて</p>
            <p>
                このメールは{{ $shop->name }}よりお送りしています。<br>
                ご予約やご利用いただき、誠にありがとうございます。
            </p>
        </div>
    </div>
</body>
</html>
