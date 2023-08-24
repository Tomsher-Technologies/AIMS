<!DOCTYPE html>
<html>
<head>
    <title>{{env('APP_NAME') }}</title>
</head>
<body>
    <p> Hi,
    <p>You can reset password from bellow link:</p>
    <p><a href="{{ route('reset.password.app', $token) }}">Click here to Reset Password</a></p>
    <p>
        Thank you,
        <br>
        Team {{env('APP_NAME')}}    
    </p>
</body>
</html>