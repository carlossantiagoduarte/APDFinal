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
    <link rel="stylesheet" href="{{ asset('styles/register.css') }}">
</head>

<body>

    <div class="register-container">

        <div class="register-info">
            <h1>Registrate!</h1>
            <p>Para poder brindarte un mejor servicio</p>
        </div>

        <div class="register-form-box">
            <img src="{{ asset('images/logo.png') }}" class="register-logo" alt="Logo">

            {{-- ACCIÓN CORREGIDA: Apunta a la ruta POST de registro --}}
            <form class="register-form" action="{{ route('register.post') }}" method="POST">
                @csrf
                <label>Nombre:</label>
                <input type="text" name="nombre" required>

                <label>Apellido:</label>
                <input type="text" name="apellido" required>

                <label>Correo Electrónico:</label>
                <input type="email" name="email" placeholder="example@gmail.com" required>

                <label>Número de celular:</label>
                <input type="tel" name="telefono" placeholder="Ej. 951 123 4567" required>

                <label>Contraseña:</label>
                <input type="password" name="password" placeholder="********" required>

                <label>Confirmar Contraseña:</label>
                <input type="password" name="password_confirmation" placeholder="********" required>

                <button type="submit" class="register-btn">Registrarte</button>
            </form>

            <p class="register-footer">CodeVisión®. Todos los derechos reservados 2025©.</p>
        </div>

    </div>
</body>

</html>
