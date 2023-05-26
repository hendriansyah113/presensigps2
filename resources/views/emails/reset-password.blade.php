<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>

<body>
    <h1>Reset Password</h1>
    <p>Silakan klik tautan berikut untuk mereset password Anda:</p>
    <a href="{{ route('password.reset', ['token' => $token, 'email' => $user->email]) }}">Reset Password</a>
    <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan pesan ini.</p>
</body>

</html>
