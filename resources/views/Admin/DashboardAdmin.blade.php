<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | CodeVision</title>
    
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
                <a href="{{ route('dashboard.admin') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9.5L12 3l9 6.5V21H3z" />
                    </svg>
                    Inicio
                </a>

                <a href="{{ route('editarperfil') }}">
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
        <h2 class="hero-title">¡Bienvenido AD!</h2>
    </section>

<section class="search-section">
        <form action="{{ route('dashboard.admin') }}" method="GET" style="width: 100%; display: flex; flex-direction: column; gap: 15px;">
            
            <div class="search-box">
                <input type="text" name="search" placeholder="Buscar por nombre o lugar..." value="{{ request('search') }}" />
                <button type="submit" class="btn-search">Buscar</button>
                
                @if(request('search') || request('filter_date'))
                    <a href="{{ route('dashboard.admin') }}" class="btn-search" style="background-color: #777; text-decoration: none; text-align: center; display:flex; align-items:center; justify-content:center;">
                        Limpiar todo
                    </a>
                @endif
            </div>

            <div class="filters">
                <select name="category">
                    <option value="">Todas las categorías</option>
                </select>
                
                <input 
                    type="date" 
                    name="filter_date" 
                    value="{{ request('filter_date') }}"
                    style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Inter', sans-serif;"
                    title="Filtrar por fecha de inicio"
                >
                
            </div>

        </form>

        <div class="filters" style="margin-top: 0; justify-content: flex-end;">
             <div class="new-event">
                <a href="{{ route('events.create') }}" class="btn-search" style="text-decoration: none; display: inline-block; text-align: center;">
                    Crear evento
                </a>
            </div>
        </div>
    </section>

    <section class="events">
        <div class="events-header">
            <h2>Eventos y concursos de tecnología</h2>
            <a href="#" class="view-all">Ver todos los eventos →</a>
        </div>

        <div class="events-grid">

            @forelse($events as $event)
                <div class="event-card">
                    <a href="#" class="card-link"></a>
                    
                    <img src="{{ $event->image_url ?? asset('images/default-event.jpeg') }}" class="event-img" alt="{{ $event->title }}" />
                    
                    <div class="event-info">
                        <p class="event-date">
                            📅 {{ \Carbon\Carbon::parse($event->start_date)->format('d M, Y') }} 
                            - {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} hrs
                        </p>
                        
                        <h3 class="event-title">{{ $event->title }}</h3>
                        
                        <p class="event-description">
                            {{ Str::limit($event->description, 90) }}
                        </p>
                        
                        <p class="event-location">📍 {{ $event->location }}</p>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #555;">
                    <h3>No hay eventos activos</h3>
                    <p>¡Sé el primero en crear un evento tecnológico!</p>
                </div>
            @endforelse

        </div>
    </section>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca.</p>
            </div>
            <div>
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li>Inicio</li>
                    <li>Eventos</li>
                    <li>Categorías</li>
                    <li>Calendario</li>
                </ul>
            </div>
            <div>
                <h3>Recursos</h3>
                <ul>
                    <li>Preguntas frecuentes</li>
                    <li>Cómo inscribirse</li>
                    <li>Políticas de evento</li>
                </ul>
            </div>
            <div>
                <h3>Contactos</h3>
                <ul>
                    <li>Inicio</li>
                    <li>Eventos</li>
                    <li>Categorías</li>
                </ul>
            </div>
        </div>
        <p class="footer-copy">© {{ date('Y') }} CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>
</html>
