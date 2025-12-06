<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a mi aplicación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 1em 0;
        }

        header h1 {
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .content {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .content .box {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 48%;
        }

        .content .box h2 {
            margin-top: 0;
        }

        .buttons {
            margin-top: 20px;
            text-align: center;
        }

        .buttons a {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .buttons a:hover {
            background-color: #45a049;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: 40px;
        }

        footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenido a nuestra aplicación</h1>
    </header>

    <div class="container">
        <div class="content">
            <div class="box">
                <h2>¿Quiénes somos?</h2>
                <p>Somos un equipo comprometido con ofrecerte las mejores soluciones digitales. Nuestro objetivo es facilitarte herramientas innovadoras para hacer crecer tu negocio.</p>
            </div>
            <div class="box">
                <h2>¿Qué hacemos?</h2>
                <p>Brindamos servicios personalizados en tecnología, con un enfoque en el desarrollo web, consultoría y soluciones a medida para tus necesidades específicas.</p>
            </div>
        </div>

        <div class="buttons">
            @if (Auth::check())

            @if (Auth::user()->hasRole('Juez'))
                <a href="{{ route('dashboard.juez') }}">Ir al Dashboard Juez</a>

            @elseif (Auth::user()->hasRole('Estudiante'))
                <a href="{{ route('dashboard.estudiante') }}">Ir al Dashboard Estudiante</a>

            @else (Auth::user()->hasRole('Admin'))
                <a href="{{ route('dashboard.admin') }}">Ir al Dashboard Admin</a>

            @endif
            

    @else
        <!-- Si no está autenticado -->
        <a href="{{ route('iniciarsesion') }}">Iniciar sesión</a>
        <a href="{{ route('registrarusuario') }}">Registrarse</a>
    @endif
</div>

    </div>

    <footer>
        <p>&copy; 2025 Mi Aplicación | <a href="#">Privacidad</a> | <a href="#">Términos</a></p>
    </footer>
</body>
</html>
