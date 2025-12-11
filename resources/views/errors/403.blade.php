<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Denegado | CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/error403.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

</head>
<body>

    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" class="logo">
            <span class="site-title">CodeVision</span>
        </div>
    </nav>

    <div class="error-container">
        <h1 class="error-code">403</h1>
        <h2 class="error-title">Acceso Restringido</h2>
        <p style="margin-bottom: 30px; color: #777; max-width: 500px;">
            Lo sentimos, no tienes permisos para acceder a esta área. <br>
            Esta sección es exclusiva para otro tipo de usuarios (Admin/Juez).
        </p>
        
        <div style="display: flex; gap: 15px;">
            <a href="javascript:history.back()" class="btn-home" style="background-color: #777;">
                ← Regresar
            </a>
            <a href="{{ route('dashboard') }}" class="btn-home">
                Ir a mi Inicio
            </a>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca.</p>
            </div>
        </div>
        <p class="footer-copy">© {{ date('Y') }} CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>
</html>