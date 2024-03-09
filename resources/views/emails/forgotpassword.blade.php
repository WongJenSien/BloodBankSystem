<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>
    <p>Dear {{ $data['email'] }},</p>
    <p>
        Please click the link below to reset your password:
        <a href="{{ $data['link'] }}">Reset Password</a>
    </p>
</body>
</html>