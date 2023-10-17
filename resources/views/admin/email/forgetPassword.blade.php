<!DOCTYPE html>
<!DOCTYPE html>
<html>
<head>
    <title>{{env('APP_NAME') }}</title>
</head>
<body>
    <p> Hi,
    <p>There was a request to change your password!</p>
        
    <p> If you did not make this request then please ignore this email.</p>
    <p> Otherwise, You can use the following button to reset your password:</p>
    <p><button class="btn btn-success" style="background-color: #28a745!important;box-sizing: border-box;color: #fff !important;text-decoration: none;display: inline-block;font-size: inherit;font-weight: 500;line-height: 1.5;white-space: nowrap;vertical-align: middle;border-radius: 0.5em;padding: 0.5em 1em;border: 1px solid #28a745;"><a href="{{ route('reset.password.get', $token) }}" style="color:#fff !important;">Reset Password</a></button></p>
    <p>
        Thank you,
        <br>
        Team {{env('APP_NAME')}}    
    </p>
</body>
</html>