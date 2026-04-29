<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>

<h1>Welcome {{ $user->name }}</h1>

<p>Your account has been successfully created.</p>

<p>You can now login and start using the platform using the credentials below:</p>

<p>
    <strong>Email:</strong> {{ $user->email }}<br>
    <strong>Password:</strong> {{ $password }}
</p>

<p>Thanks,<br>
{{ config('app.name') }}</p>

</body>
</html>