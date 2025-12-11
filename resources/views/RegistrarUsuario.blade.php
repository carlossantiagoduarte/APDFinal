<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CodeVision</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles/register.css') }}">
</head>

<body>

    <div class="register-container">

        <div class="register-info">
            <h1>¡Regístrate!</h1>
            <p>Para poder brindarte un mejor servicio</p>
        </div>

        <div class="register-form-box">
            <img src="{{ asset('images/logo.png') }}" class="register-logo" alt="Logo">

            {{-- MENSAJE GENERAL DE ERROR --}}
            @if ($errors->any())
                <div style="
                    background:#ffdddd; 
                    color:#b30000; 
                    padding:10px; 
                    border-radius:8px; 
                    margin-bottom:15px; 
                    font-weight:bold;
                    text-align:center;">
                    Por favor corrige los errores marcados.
                </div>
            @endif

            <form class="register-form" action="{{ route('register.post') }}" method="POST">
                @csrf

                <label>Nombre:</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <p style="color:red; font-size:14px; margin:5px 0;">{{ $message }}</p>
                @enderror

                <label>Apellido:</label>
                <input type="text" name="apellido" value="{{ old('apellido') }}" required>
                @error('apellido')
                    <p style="color:red; font-size:14px; margin:5px 0;">{{ $message }}</p>
                @enderror

                <label>Correo Electrónico:</label>
                <input type="email" name="email" placeholder="example@gmail.com" value="{{ old('email') }}" required>
                @error('email')
                    <p style="color:red; font-size:14px; margin:5px 0;">{{ $message }}</p>
                @enderror

                <label>Número de celular:</label>
                <input type="tel" name="telefono" placeholder="Ej. 951 123 4567" value="{{ old('telefono') }}" required>
                @error('telefono')
                    <p style="color:red; font-size:14px; margin:5px 0;">{{ $message }}</p>
                @enderror

                <label>Contraseña:</label>
                <input type="password" name="password" placeholder="********" required>
                @error('password')
                    <p style="color:red; font-size:14px; margin:5px 0;">{{ $message }}</p>
                @enderror

                <label>Confirmar Contraseña:</label>
                <input type="password" name="password_confirmation" placeholder="********" required>

                <button type="submit" class="register-btn">Registrarte</button>

                <!-- NUEVOS BOTONES -->
                <div style="display:flex; flex-direction:column; gap:10px; margin-top:15px;">

                    <!-- Botón: Iniciar Sesión -->
                    <a href="{{ route('login.submit') }}"
                        style="
                            display:block;
                            text-align:center;
                            padding:10px;
                            border-radius:8px;
                            background-color:#2a3b8d;
                            color:white;
                            font-weight:bold;
                            text-decoration:none;
                        ">
                        Iniciar sesión
                    </a>

                    <!-- Botón: Volver al inicio -->
                    <a href="{{ route('home') }}"
                        style="
                            display:block;
                            text-align:center;
                            padding:10px;
                            border-radius:8px;
                            background-color:#555;
                            color:white;
                            font-weight:bold;
                            text-decoration:none;
                        ">
                        Volver al inicio
                    </a>

                </div>

            </form>

            <p class="register-footer">CodeVision®. Todos los derechos reservados 2025©.</p>
        </div>

    </div>
</body>
</html>
