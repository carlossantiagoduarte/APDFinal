<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Solicitudes Pendientes | CodeVision</title>
    {{-- Asegúrate de que la ruta del CSS sea correcta (usar asset() siempre es mejor) --}}
    <link rel="stylesheet" href="{{ asset('styles/solicitudes.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
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

    <nav class="navbar">

        <div class="navbar-left">
            {{-- ENLACE AL DASHBOARD ESTUDIANTE --}}
            <a href="{{ route('dashboard.estudiante') }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo">
                <span class="site-title">CodeVision</span>
            </a>
        </div>

        <div class="user-menu-container">

            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}

                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            <div id="user-menu" class="dropdown">
                <a href="{{ route('dashboard.estudiante') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 9.5L12 3l9 6.5V21H3z" />
                    </svg>
                    Inicio (Estudiante)
                </a>

                {{-- RUTA CORREGIDA: editarperfil -> profile.edit --}}
                <a href="{{ route('profile.edit') }}"> 
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="7" r="4" />
                        <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
                    </svg>
                    Perfil
                </a>
                
                {{-- ENLACE ACTIVO (Ya estamos en teams.requests) --}}
                <a style="background-color: #f0f0f0;">
                     <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M8 12l3 3 5-6" /></svg>
                    Solicitudes
                </a>

                <form action="{{ route('logout') }}" method="POST" style="display: block;">
                    @csrf <button type="submit" class="btn-search" style="color: black; background-color: #FFFFFF; padding: 12px 18px; text-decoration: none; border: none; border-radius: 10px; cursor: pointer; display: flex; align-items: center; gap: 10px; width: 100%;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px;">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>

        </div>

    </nav>
    
    {{-- Mensajes de Éxito o Error --}}
    @if(session('success'))
        <div style="background-color:#d4edda; color:#155724; padding:10px; border-radius:5px; margin: 20px auto; width: 80%; text-align: center;">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background-color:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin: 20px auto; width: 80%; text-align: center;">
            {{ $errors->first() }}
        </div>
    @endif


    <section class="solicitudes-container">

        <h2 class="title">Solicitudes pendientes para el equipo: {{ $miEquipo->name ?? 'N/A' }}</h2>

        <div class="tabla-solicitud">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Carrera</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($solicitudes as $usuario)
                        <tr>
                            <td>{{ $usuario->name }} {{ $usuario->lastname }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->carrera ?? 'N/A' }}</td>
                            <td class="acciones">
                                
                                {{-- Formulario para ACEPTAR --}}
                                <form action="{{ route('teams.respond', $usuario->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <input type="hidden" name="accion" value="aceptar">
                                    <button type="submit" class="btn aceptar">Aceptar</button>
                                </form>

                                {{-- Formulario para RECHAZAR --}}
                                <form action="{{ route('teams.respond', $usuario->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <input type="hidden" name="accion" value="rechazar">
                                    <button type="submit" class="btn rechazar">Rechazar</button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #777;">No hay solicitudes pendientes en este momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </section>

    <footer class="footer">
        <div class="footer-grid">

            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca para gestión de eventos tecnológicos.</p>
            </div>
            
            {{-- Se pueden añadir enlaces de manera dinámica --}}
            <div>
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="{{ route('dashboard.estudiante') }}">Inicio</a></li>
                    <li><a href="#">Eventos</a></li>
                    <li><a href="#">Categorías</a></li>
                    <li><a href="#">Calendario</a></li>
                </ul>
            </div>

            <div>
                <h3>Recursos</h3>
                <ul>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Cómo inscribirse</a></li>
                    <li><a href="#">Políticas de evento</a></li>
                </ul>
            </div>

            <div>
                <h3>Contactos</h3>
                <ul>
                    <li><a href="#">Información de Contacto</a></li>
                    <li><a href="#">Ubicación</a></li>
                </ul>
            </div>

        </div>

        <p class="footer-copy">© {{ date('Y') }} CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>

</html>
