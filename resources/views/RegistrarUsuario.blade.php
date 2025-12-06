<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CodeVision</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="styles/register.css">
</head>

<body>
    <!-- FLECHA DE REGRESO -->
    <a href="{{ route('login') }}" class="register-back-arrow">←</a>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="register-container">

        <!-- TEXTOS IZQUIERDA -->
        <div class="register-info">
            <h1>Registrate!</h1>
            <p>Para poder brindarte un mejor servicio</p>
        </div>

        <!-- FORMULARIO -->
        <div class="register-form-box">
            <img src="{{ asset('images/logo.png') }}" class="register-logo" alt="Logo">

            <form class="register-form">
                <label>Nombre:</label>
                <input type="text">

                <label>Apellido:</label>
                <input type="text">

                <label>Correo Electrónico:</label>
                <input type="email" placeholder="example@gmail.com">

                <label>Número de celular:</label>
                <input type="tel" placeholder="Ej. 951 123 4567">

                <label>Contraseña:</label>
                <input type="password" placeholder="********">

                <label>Confirmar Contraseña:</label>
                <input type="password" placeholder="********">

                <button type="submit" class="register-btn">Registrarte</button>
            </form>

            <p class="register-footer">CodeVisión®. Todos los derechos reservados 2025©.</p>
        </div>

    </div>
</body>

</html>
