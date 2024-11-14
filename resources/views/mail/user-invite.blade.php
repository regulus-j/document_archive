<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Invitation</title>
</head>
<body>
    <h1>Hello, {{ $name }}</h1>
    <p>You have been invited to join our platform. Here are your details:</p>
    <ul>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
        <li><strong>Role:</strong> {{ $role }}</li>
    </ul>
    <p>You can log in using the following link:</p>
    <a href="{{ $loginLink }}">{{ $loginLink }}</a>
    <p>We look forward to having you on board!</p>
</body>
</html>