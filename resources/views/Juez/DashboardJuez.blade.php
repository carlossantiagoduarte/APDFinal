<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Juez | CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/dashboard.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
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
            <img src="{{ asset('images/logo.png') }}" class="logo" alt="CodeVision Logo">
            <span class="site-title">CodeVision (Juez)</span>
        </div>

        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            <div id="user-menu" class="dropdown">
                <a href="{{ route('dashboard.juez') }}">
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

    <section class="hero">
        <h2 class="hero-title">Bienvenido Juez</h2>
    </section>

    <section class="events">
        <div class="events-header">
            <h2>Selecciona un evento</h2>
        </div>

        <div class="events-grid">
            @if($events->count() > 0)
                @foreach($events as $event)
                    <div class="event-card">
                        
                        <button 
                            onclick="window.location='{{ route('juez.equipos', $event->id) }}'" 
                            class="card-link"
                            style="all: unset; width: 100%; background: none; border: none; cursor: pointer;">
                            
                            <img src="{{ $event->image_url ?? asset('images/default-event.jpg') }}" 
                                 class="event-img" 
                                 alt="{{ $event->title }}"
                                 onerror="this.src='{{ asset('images/logo.png') }}'">
                             
                            <div class="event-info">
                                <p class="event-date">
                                    📅 {{ \Carbon\Carbon::parse($event->start_date)->format('d M, Y') }} 
                                </p>

                                <h3 class="event-title">{{ $event->title }}</h3>

                                <p class="event-description">
                                    {{ \Illuminate\Support\Str::limit($event->description, 100) }}
                                </p>

                                <p class="event-location">📍 {{ $event->location }}</p>
                                
                                <p style="font-size: 0.9rem; color: green; font-weight:bold; margin-top: 10px;">
                                    👉 Click para calificar equipos
                                </p>
                            </div>
                        </button>
                    </div>
                @endforeach
            @else
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <h3>No hay eventos activos para calificar 😢</h3>
                </div>
            @endif
        </div>
    </section>

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
