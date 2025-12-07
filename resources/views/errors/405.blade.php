<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada | CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/dashboard.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        .error-container {
            text-align: center;
            padding: 80px 20px;
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .error-code {
            font-size: 6rem;
            font-family: 'Jomolhari', serif;
            color: #333;
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #555;
        }
        .btn-home {
            background-color: #333;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
        }
        .btn-home:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" class="logo">
            <span class="site-title">CodeVision</span>
        </div>
    </nav>

    <div class="error-container">
        <h1 class="error-code">405</h1>
        <h2 class="error-title">Nooooo Error Interno del Servidor.</h2>
        <p style="margin-bottom: 30px; color: #777;">
            Parece que el enlace está roto o la página ha sido movida.
        </p>
        
        <a href="{{ route('dashboard') }}" class="btn-home">
            Volver al Inicio
        </a>
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
