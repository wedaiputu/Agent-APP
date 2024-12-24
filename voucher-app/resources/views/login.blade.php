<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beautiful Login Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 90%;
        }

        .form-container h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
            text-align: center;
            color: #fff;
        }

        .form-container label {
            font-weight: bold;
            font-size: 1rem;
            display: block;
            margin-bottom: 10px;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.7);
            box-shadow: inset 0px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .form-container input:focus {
            border-color: #2575fc;
            outline: none;
            box-shadow: 0px 0px 5px #2575fc;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background: #6a11cb;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .form-container button:hover {
            background: #2575fc;
        }

        .errors {
            margin-top: 20px;
            background: rgba(255, 0, 0, 0.2);
            padding: 15px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
        }

        .errors ul {
            margin: 0;
            padding: 0;
            list-style-type: none;
        }

        .errors li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="ip">IP Address:</label>
            <input type="text" name="ip" required placeholder="Enter IP Address">
            <label for="username">Username:</label>
            <input type="text" name="username" required placeholder="Enter Username">
            <label for="password">Password:</label>
            <input type="password" name="password" required placeholder="Enter Password">
            <button type="submit">Login</button>
        </form>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</body>
</html>
