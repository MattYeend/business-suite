<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>

<h1>Welcome {{ $user->name }}</h1>

<p>Your account has been successfully created.</p>

@if($password)
<p>You can now login and start using the platform using the credentials below:</p>

<p>
    <strong>Email:</strong> {{ $user->email }}<br>
    <strong>Password:</strong> {{ $password }}
</p>
@else
<p>Please check your email for password reset instructions to set up your account.</p>
@endif

<p>Thanks,<br>
{{ config('app.name') }}</p>

</body>
</html>