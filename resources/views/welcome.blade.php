<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a CodeVision</title>
    <style>
        :root {
            --primary-color: #4CAF50; /* Verde principal */
            --secondary-color: #333; /* Texto oscuro/footer */
            --background-color: #f7f7f7;
            --card-bg: #fff;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --font-family: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--background-color);
            color: var(--secondary-color);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 2.5em 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            margin: 0;
            font-size: 2.5em;
        }

        .container {
            max-width: 1100px;
            margin: 20px auto;
            padding: 20px;
            flex-grow: 1; /* Permite que el contenedor crezca */
        }

        .content {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            margin-top: 40px;
        }

        .content .box {
            background-color: var(--card-bg);
            padding: 30px;
            box-shadow: var(--box-shadow);
            width: 50%;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out;
        }
        
        .content .box:hover {
            transform: translateY(-5px);
        }

        .content .box h2 {
            margin-top: 0;
            color: var(--primary-color);
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            font-weight: 700;
        }

        .buttons {
            margin-top: 60px;
            padding: 30px;
            text-align: center;
            background-color: var(--card-bg);
            border-radius: 10px;
            box-shadow: var(--box-shadow);
        }
        
        .buttons p {
            margin-bottom: 20px;
            font-size: 1.1em;
            font-weight: 600;
        }

        .buttons a {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 12px 25px;
            margin: 10px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.1s;
        }

        .buttons a:hover {
            background-color: #3e8e41; /* Verde más oscuro */
            transform: translateY(-1px);
        }
        
        .buttons a:nth-child(2) { /* Estilo secundario para registrarse */
            background-color: #333;
        }
        
        .buttons a:nth-child(2):hover {
            background-color: #000;
        }

        footer {
            background-color: var(--secondary-color);
            color: white;
            text-align: center;
            padding: 15px 0;
            width: 100%;
            margin-top: auto; /* Empuja el footer hacia abajo */
        }

        footer a {
            color: var(--primary-color);
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content {
                flex-direction: column;
            }
            .content .box {
                width: 100%;
                margin-bottom: 20px;
            }
            .buttons a {
                display: block;
                width: 80%;
                margin: 10px auto;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>CodeVision</h1>
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
                <p>¡Hola, {{ Auth::user()->name }}! Navega a tu panel:</p>
                @if (Auth::user()->hasRole('Juez'))
                    <a href="{{ route('dashboard.juez') }}">Ir al Dashboard Juez</a>

                @elseif (Auth::user()->hasRole('Estudiante'))
                    <a href="{{ route('dashboard.estudiante') }}">Ir al Dashboard Estudiante</a>

                @else 
                    <a href="{{ route('dashboard.admin') }}">Ir al Dashboard Admin</a>

                @endif
            @else
                <p>Únete a nuestra plataforma de eventos tecnológicos.</p>
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register.view') }}">Registrarse</a>
            @endif
        </div>

    </div>

    <footer>
        <p>&copy; 2025 CodeVision | <a href="#">Privacidad</a> | <a href="#">Términos</a></p>
    </footer>
</body>
</html>
