<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            width: 100%;
            padding: 40px 0;
            background-color: #f5f6fa;
        }

        .email-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .header {
            font-size: 22px;
            color: #555;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .subheader {
            font-size: 16px;
            color: #777;
            margin-bottom: 30px;
        }

        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #4a90e2;
            background-color: #e9f2fc;
            padding: 15px;
            border-radius: 6px;
            display: inline-block;
            letter-spacing: 4px;
        }

        .message {
            font-size: 14px;
            color: #555;
            margin-top: 30px;
            line-height: 1.6;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">Votre Code de Sécurité</div>
            <div class="subheader">Utilisez le code ci-dessous pour vous connecter</div>
            <div class="otp-code">{{ $otpCode }}</div>
            <div class="message">
                Ce code est valable pendant les 5 prochaines minutes.<br>
                Si vous n’avez pas initié cette demande, veuillez ignorer cet e-mail.
            </div>
            <div class="footer">© 2024 Boosteno</div>
        </div>
    </div>
</body>

</html>