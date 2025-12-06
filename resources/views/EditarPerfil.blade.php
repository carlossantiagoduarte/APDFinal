<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Perfil | CodeVision</title>

    <link rel="stylesheet" href="{{ asset('styles/perfil.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById("user-toggle");
            const menu = document.getElementById("user-menu");

            toggle.addEventListener("click", () => {
                toggle.classList.toggle("active");
                menu.classList.toggle("show");
            });

            document.addEventListener("click", (e) => {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    toggle.classList.remove("active");
                    menu.classList.remove("show");
                }
            });
        });
    </script>
</head>

<body>

    {{-- ===================== NAVBAR ===================== --}}
    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" class="logo">
            <span class="site-title">CodeVision</span>
        </div>

        <div class="user-menu-container">

            {{-- ===== NOMBRE DEL USUARIO ===== --}}
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}

                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            {{-- ===================== MENU DESPLEGABLE ===================== --}}
            <div id="user-menu" class="dropdown">

                {{-- === RUTA SEGÚN ROL === --}}
                @if (Auth::user()->hasRole('Juez'))
                    <a href="{{ route('dashboard.juez') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9.5L12 3l9 6.5V21H3z" />
                        </svg>
                        Inicio (Juez)
                    </a>

                @elseif (Auth::user()->hasRole('Estudiante'))
                    <a href="{{ route('dashboard.estudiante') }}">
                        <svg viewBox="0 0 24 24 24" fill="none" stroke="#111" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9.5L12 3l9 6.5V21H3z" />
                        </svg>
                        Inicio (Estudiante)
                    </a>

                    
                <a href="{{ route('solicitudesequipo') }}"><!-- Enlace actualizado a las solicitudes -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M8 12l3 3 5-6" />
                    </svg>
                    </svg>
                    Solicitudes
                </a>

                @elseif (Auth::user()->hasRole('Admin'))
                    <a href="{{ route('dashboard.admin') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9.5L12 3l9 6.5V21H3z" />
                        </svg>
                        Inicio (Admin)
                    </a>
                @endif


                {{-- LOGOUT --}}
                <form action="{{ route('logout') }}" method="POST" style="display: block;">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit();" style="display:flex; align-items:center; gap:10px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"
                            style="width:20px;" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Cerrar sesión
                    </a>
                </form>

            </div>

        </div>
    </nav>

    <a href="{{ url()->previous() }}" class="profile-back-arrow">←</a>

    {{-- ===================== CONTENIDO ===================== --}}
    <div class="profile-container">

        <div class="profile-info">
            <h1>Editar Perfil</h1>
            <p>Modifica tu información personal</p>

            {{-- MENSAJES DE ÉXITO --}}
            @if(session('success'))
                <div style="background-color:#d4edda; color:#155724; padding:10px; border-radius:5px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ERRORES --}}
            @if ($errors->any())
                <div style="background-color:#f8d7da; color:#721c24; padding:10px; border-radius:5px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- FORMULARIO EDITAR PERFIL --}}
        <div class="profile-form-box">
            <img src="{{ asset('images/logo.png') }}" class="profile-logo">

            <form class="profile-form" action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <label>Nombre:</label>
                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required>

                <label>Apellido:</label>
                <input type="text" name="lastname" value="{{ old('lastname', Auth::user()->lastname) }}">

                <label>Correo Electrónico:</label>
                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>

                <label>Número de celular:</label>
                <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}">

                <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">
                <p style="font-size: 0.9em; color: #666;">Deja las contraseñas vacías si no quieres cambiarlas.</p>

                <label>Nueva Contraseña:</label>
                <input type="password" name="password" placeholder="Opcional">

                <label>Confirmar Contraseña:</label>
                <input type="password" name="password_confirmation" placeholder="Opcional">

                <button type="submit" class="profile-btn">Guardar Cambios</button>

            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca para gestión de eventos tecnológicos.</p>
            </div>
            <div>
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li>Inicio</li>
                    <li>Eventos</li>
                </ul>
            </div>
            <div>
                <h3>Recursos</h3>
                <ul>
                    <li>Preguntas frecuentes</li>
                </ul>
            </div>
            <div>
                <h3>Contactos</h3>
                <ul>
                    <li>Inicio</li>
                </ul>
            </div>
        </div>
        <p class="footer-copy">© {{ date('Y') }} CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>
</html>
