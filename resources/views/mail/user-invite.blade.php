<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Invitation</title>
</head>

<body>
    <h1>Hello, {{ $name }}</h1>
    <p>You have been invited to join our platform. Here are your details:</p>
    <ul>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>
    <p>You can log in using the following link:</p>
    <a href="{{ $loginLink }}">{{ $loginLink }}</a>
    <p>We look forward to having you on board!</p>
</body>

</html>