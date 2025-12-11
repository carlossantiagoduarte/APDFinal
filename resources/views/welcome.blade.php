<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a CodeVision</title>
<link rel="stylesheet" href="{{ asset('styles/welcome.css') }}">

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
                
                {{-- CORRECCIÓN AQUÍ: Usar nombres de rol en minúscula (judge, student, admin) --}}
                
                @if (Auth::user()->role === 'judge' || Auth::user()->hasRole('judge'))
                    <a href="{{ route('dashboard.juez') }}">Ir al Dashboard Juez</a>

                @elseif (Auth::user()->role === 'student' || Auth::user()->hasRole('student'))
                    <a href="{{ route('dashboard.estudiante') }}">Ir al Dashboard Estudiante</a>

                @elseif (Auth::user()->role === 'admin' || Auth::user()->hasRole('admin')) 
                    <a href="{{ route('dashboard.admin') }}">Ir al Dashboard Admin</a>

                @else
                    {{-- Caso por defecto si el rol no coincide o no tiene rol --}}
                    <a href="{{ route('dashboard') }}">Ir al Dashboard General</a>
                @endif

            @else
                <div class="auth-buttons">
                    <p>Únete a nuestra plataforma de eventos tecnológicos.</p>
                    <a href="{{ route('login') }}">Iniciar sesión</a>
                    <a href="{{ route('register.view') }}">Registrarse</a>
                </div>
            @endif
        </div>

    </div>

    <footer>
        <p>&copy; 2025 CodeVision | <a href="#">Privacidad</a> | <a href="#">Términos</a></p>
    </footer>
</body>
</html>