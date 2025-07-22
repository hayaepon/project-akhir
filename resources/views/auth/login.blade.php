<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container img {
            max-height: 60px;
            margin: 0 auto 20px;
            display: block;
        }

        .login-container h2 {
            margin-bottom: 24px;
            font-weight: 700;
            color: #333;
            text-align: center;
        }


        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .login-container input:focus {
            border-color: #0044cc;
            outline: none;
        }

        .login-container button {
            width: 100%;
            background-color: #0044cc;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .login-container button:hover {
            background-color: #003399;
        }

        .error-message {
            background-color: #ffe5e5;
            color: #cc0000;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        @media (max-width: 500px) {
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="{{ asset('img/logo.png') }}" alt="STMIK Logo">
        <h2>Sign In</h2>

        @if($errors->any())
            <div class="error-message">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign in</button>
        </form>
    </div>
</body>

</html>