<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CodeVision</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- SOLO ROUTA CSS CORREGIDA -->
    <link rel="stylesheet" href="styles/login.css">
</head>

<body>

    <div class="container">
        <!-- IZQUIERDA -->
        <div class="left">
            <div class="login-card">
                <!-- SOLO RUTA DE IMAGEN CORREGIDA -->
                <img src="images/logo.png" class="logo">

                <h2>Iniciar sesión</h2>

                <form method="POST" action="{{ route('iniciarsesion.post') }}">
    @csrf
    <label>Correo Electrónico:</label>
    <input type="email" name="email" placeholder="example@gmail.com" required>

    <label>Contraseña:</label>
    <input type="password" name="password" placeholder="••••••••" required>

    @if($errors->any())
        <div class="error-message">
            <p>{{ $errors->first('error') }}</p>
        </div>
    @endif

    <button type="submit" class="btn-login">Iniciar sesión</button>
</form>



                    <p class="register">
                        <!-- RUTA ELIMINADA -->
                        <a href="{{ route('registrarusuario') }}">¿Aún no estás en CodeVision? Regístrate</a>
                    </p>
                </form>

                <footer>CodeVision® Todos los derechos reservados 2025©</footer>
            </div>
        </div>

        <!-- DERECHA -->
        <div class="right">
            <div class="welcome">
                <h1>Bienvenido!</h1>
                <p>
                    Aquí puedes inscribirte a cualquier evento o hackathon
                    organizado por el Instituto Tecnológico de Oaxaca
                </p>
            </div>
        </div>
    </div>

    <!-- SCRIPT ELIMINADO -->

</body>

</html>
