<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <p>Click the following link to reset your password:</p>
    <a href="{{ route('password.reset', ['token' => $token]) }}">Reset Password</a>
</body>
</html>