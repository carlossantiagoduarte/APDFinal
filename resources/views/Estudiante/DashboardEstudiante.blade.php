<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Estudiante | CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/dashboard.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

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
            <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">
            <span class="site-title">CodeVision</span>
        </div>

        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            <div id="user-menu" class="dropdown">
                <a href="{{ route('dashboard.estudiante') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5L12 3l9 6.5V21H3z" /></svg>
                    Inicio
                </a>
                
                {{-- RUTA CORREGIDA: editarperfil -> profile.edit --}}
                <a href="{{ route('profile.edit') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4" /><path d="M5.5 21a6.5 6.5 0 0 1 13 0" /></svg>
                    Perfil
                </a>

                {{-- RUTA CORREGIDA: solicitudesequipo -> teams.requests --}}
                <a href="{{ route('teams.requests') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9" /><path d="M8 12l3 3 5-6" /></svg>
                    Solicitudes
                </a>

                <form action="{{ route('logout') }}" method="POST" style="display: block;">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit();" style="display: flex; align-items: center; gap: 10px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /><polyline points="16 17 21 12 16 7" /><line x1="21" y1="12" x2="9" y2="12" /></svg>
                        Cerrar sesión
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <section class="hero">
        <h2 class="hero-title">¡Bienvenido Est!</h2>
    </section>

    <section class="search-section">
        <form action="{{ route('dashboard.estudiante') }}" method="GET" style="width: 100%;">
            <div class="search-box">
                <input type="text" name="search" placeholder="Buscar Eventos, Categorías o Tecnologías..." value="{{ request('search') }}" />
                <button type="submit" class="btn-search">Buscar</button>
            </div>

            @if(request('search'))
                <div style="text-align: center; margin-top: 10px;">
                    <a href="{{ route('dashboard.estudiante') }}" style="color: #555; text-decoration: underline;">
                        Limpiar filtros
                    </a>
                </div>
            @endif
        </form>
    </section>

    <section class="events">
        <div class="events-header">
            <h2>Eventos y Resultados Recientes</h2>
        </div>

        <div class="events-grid">
            @if(isset($events) && $events->count() > 0)
                @foreach($events as $event)
                    <div class="event-card">
                        {{-- RUTA CORREGIDA: estudiante.evento.ver -> student.event.show --}}
                        <button onclick="window.location='{{ route('student.event.show', $event->id) }}'" 
                                class="card-link" style="all: unset; width: 100%; cursor: pointer;">
                            <img src="{{ $event->image_url ?? asset('images/default-event.jpg') }}" 
                                 class="event-img" alt="{{ $event->title }}"
                                 onerror="this.src='{{ asset('images/logo.png') }}'">
                            <div class="event-info">
                                @if(!$event->is_active)
                                    <p style="color: #d9534f; font-weight: bold; margin-bottom: 5px;">
                                        🛑 Evento Finalizado
                                    </p>
                                @endif
                                <p class="event-date">📅 {{ \Carbon\Carbon::parse($event->start_date)->format('d M, Y') }}</p>
                                <h3 class="event-title">{{ $event->title }}</h3>
                                <p class="event-description">{{ \Illuminate\Support\Str::limit($event->description, 80) }}</p>
                                <p class="event-location">📍 {{ $event->location }}</p>
                                
                                <p style="color: blue; font-size: 0.9em; margin-top: 10px; font-weight: bold;">
                                    👉 Ver detalles y participar
                                </p>
                            </div>
                        </button>
                    </div>
                @endforeach
            @else
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <h3>No hay eventos activos en este momento.</h3>
                    @if(request('search'))
                        <p>Intenta con otra palabra clave.</p>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <footer class="footer">
        <p class="footer-copy">© {{ date('Y') }} CodeVision</p>
    </footer>

</body>
</html>
