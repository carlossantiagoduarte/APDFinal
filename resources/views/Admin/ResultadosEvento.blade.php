<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resultados: {{ $evento->name }} | CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/equipos.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

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

    <style>
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            margin: 20px 0 0 40px; /* Ajusta según tu diseño */
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.2s;
        }
        .back-link:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            <a href="{{ route('dashboard.admin') }}" style="text-decoration: none; display: flex; align-items: center; color: inherit; gap: 10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">
                <span class="site-title">CodeVision</span>
            </a>
        </div>

        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            <div id="user-menu" class="dropdown">
                <a href="{{ route('dashboard.admin') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9.5L12 3l9 6.5V21H3z" />
                    </svg>
                    Inicio
                </a>

                <a href="{{ route('editarperfil') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="7" r="4" />
                        <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
                    </svg>
                    Perfil
                </a>

                <form action="{{ route('logout') }}" method="POST" style="display: block;">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit();" style="display: flex; align-items: center; gap: 10px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px;">
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

    <a href="javascript:history.back()" class="back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Regresar
    </a>

    <h1>Panel administrativo - {{ $evento->name }}</h1>

    <table id="tablaEquipos">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre del Equipo</th>
                <th>Integrantes</th> 
                <th>Calificación Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipos as $index => $equipo)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    
                    <td>{{ $equipo->name }}</td>

                    <td>{{ $equipo->users->count() }} / {{ $equipo->max_members }}</td>

                    <td style="font-weight: bold; text-align: center;">
                        @if($equipo->evaluations->count() > 0)
                            {{ $equipo->evaluations->sum('score') }} pts
                        @else
                            <span style="color: gray; font-size: 0.9em;">Sin evaluar</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">
                        No hay equipos registrados en este evento todavía.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="admin-box" style="text-align: right; margin-top: 20px; display: flex; justify-content: flex-end; padding: 0 10%; gap: 15px;">
        
        <button class="btn-admin" onclick="window.location.href='{{ route('dashboard.admin') }}'" style="background-color: #555;">
             Ir al Dashboard
        </button>

        <button class="btn-admin" onclick="window.location.href='{{ route('events.edit', $evento->id) }}'">
    Ver información del evento
</button>

    </div>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca para gestión de eventos tecnológicos.</p>
            </div>
        </div>
        <p class="footer-copy">© {{ date('Y') }} CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>
</html>
