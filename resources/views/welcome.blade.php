<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenido</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #0851f0, #f53333);
            color: white;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }

        .container {
            max-width: 600px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            animation: fadeIn 2s ease-in-out;
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        a {
            font-size: 1rem;
            color: #ffed4a;
            text-decoration: none;
            padding: 10px 20px;
            border: 2px solid #ffed4a;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        a:hover {
            background-color: #ffed4a;
            color: #333;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        img {
            max-height: 200px;
            max-width: 200px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Bienvenido a Nuestra APP!</h1>
        <p>"La Actitud Positiva Es El Camino Al Exito"</p>
        
        @if (Route::has('login'))
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}">Ir al Panel</a>
                @else
                    <a href="{{ route('login') }}">Iniciar Sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" style="margin-left: 10px;">Registrarme</a>
                    @endif
                @endauth
            </div>
        @endif
        
        <a href="/">
            <img src="{{ asset('theme/images/icon/logo2.png') }}" alt="Logo">
        </a>
    </div>
</body>
</html>
