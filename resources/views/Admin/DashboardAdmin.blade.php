<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | CodeVision</title>

    <link rel="stylesheet" href="{{ asset('styles/dashboardadmin.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById("user-toggle");
            const menu = document.getElementById("user-menu");

            if(toggle && menu){
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
            }
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
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9" /></svg>
            </div>

            <div id="user-menu" class="dropdown">
                <a style="background-color: #f0f0f0;">Inicio (Admin)</a>
                <a href="{{ route('profile.edit') }}">Perfil</a>
                <a href="{{ route('gestion') }}">Gestión de Usuarios</a>
                <form action="{{ route('logout') }}" method="POST" style="display: block;">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit();">Cerrar sesión</a>
                </form>
            </div>
        </div>
    </nav>

    <section class="hero">
        <h2 class="hero-title">¡Bienvenido Ad!</h2>
    </section>

    <section class="search-section">
        <form action="{{ route('dashboard.admin') }}" method="GET" style="width: 100%; display: flex; flex-direction: column; gap: 15px;">
            
            <div class="search-box" style="display: flex; gap: 10px;">
                <input type="text" name="search" placeholder="Buscar por nombre, lugar u organizador..." 
                       value="{{ request('search') }}" style="flex: 1;" />
                
                <button type="submit" class="btn-search">Buscar</button>

                {{-- Botón de Limpiar filtros --}}
                @if (request('search') || request('filter_date') || request('category'))
                    <a href="{{ route('dashboard.admin') }}" class="btn-search" 
                       style="background-color: #777; text-decoration: none; text-align: center; display:flex; align-items:center; justify-content:center;">
                        Limpiar todo
                    </a>
                @endif
            </div>

            <div class="filters" style="display: flex; gap: 15px; align-items: center;">
                
                {{-- Filtro de Fecha --}}
                <div style="display: flex; flex-direction: column;">
                    <span style="font-size: 0.8em; color: #666; margin-bottom: 2px;">Fecha Inicio:</span>
                    <input type="date" name="filter_date" value="{{ request('filter_date') }}" 
                           onchange="this.form.submit()"
                           style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Inter', sans-serif;" 
                           title="Filtrar por fecha de inicio">
                </div>

                {{-- NUEVO: Filtro de Categoría --}}
                <div style="display: flex; flex-direction: column; min-width: 200px;">
                    <span style="font-size: 0.8em; color: #666; margin-bottom: 2px;">Categoría:</span>
                    <select name="category" onchange="this.form.submit()" 
                            style="padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Inter', sans-serif; background: white;">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-left: auto;">
                    <a href="{{ route('events.create') }}" class="btn-search" 
                       style="text-decoration: none; display: inline-block; text-align: center; background-color: #28a745;">
                        + Crear evento
                    </a>
                </div>
            </div>

        </form>
    </section>

    <section class="events">
        <div class="events-header">
            <h2>Eventos y Resultados Recientes</h2>
            @if(request('search') || request('category') || request('filter_date'))
                <span style="font-size: 0.9em; color: #666; margin-left: 10px;">
                    (Mostrando resultados filtrados: {{ count($events) }})
                </span>
            @endif
        </div>

        <div class="events-grid">
            @if ($events->count() > 0)
                @foreach ($events as $event)
                    <div class="event-card">
                        <button onclick="window.location='{{ route('events.results', $event->id) }}'" class="card-link" style="all: unset; width: 100%; background: none; border: none; cursor: pointer;">
                            
                            <img src="{{ $event->image_url ?? asset('images/default-event.jpg') }}" class="event-img" alt="{{ $event->title }}" onerror="this.src='{{ asset('images/logo.png') }}'">

                            <div class="event-info">
                                <p class="event-date">
                                    📅 {{ \Carbon\Carbon::parse($event->start_date)->format('d M, Y') }} 
                                    - {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} hrs
                                </p>

                                <h3 class="event-title">{{ $event->title }}</h3>
                                
                                {{-- Badge de Categoría --}}
                                <span style="background: #eef; color: #33a; padding: 2px 8px; border-radius: 10px; font-size: 0.75em; font-weight: bold;">
                                    {{ $event->main_category }}
                                </span>

                                <p class="event-description">
                                    {{ \Illuminate\Support\Str::limit($event->description, 100) }}
                                </p>

                                <p class="event-location">📍 {{ $event->location }}</p>

                                @if (!$event->is_active)
                                    <p style="color: #d9534f; font-weight: bold; margin-top: 10px;">🛑 Evento Finalizado</p>
                                @endif

                                <p style="font-size: 0.8rem; color: #666; margin-top: 5px;">
                                    Organiza: {{ $event->organizer }}
                                </p>
                            </div>
                        </button>
                    </div>
                @endforeach
            @else
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <h3>No se encontraron eventos con esos filtros 🔍</h3>
                    <p>Intenta una búsqueda diferente o crea un nuevo evento.</p>
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