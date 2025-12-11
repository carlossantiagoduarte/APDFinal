<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles: {{ $event->title }}</title>
    
    <link rel="stylesheet" href="{{ asset('styles/event-information.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* Estilo simple para la tabla de criterios pública */
        .criteria-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }
        .criteria-tag {
            background: #eef2f5;
            color: #333;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            border: 1px solid #ddd;
            font-weight: 500;
        }
        .criteria-points {
            color: #28a745;
            font-weight: bold;
            margin-left: 5px;
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="navbar-left">
            {{-- Lógica de redirección según rol --}}
            @php
                $dashboardRoute = route('home');
                if(Auth::check()){
                    if(Auth::user()->role === 'admin') $dashboardRoute = route('dashboard.admin');
                    elseif(Auth::user()->role === 'judge') $dashboardRoute = route('dashboard.juez');
                    else $dashboardRoute = route('dashboard.estudiante');
                }
            @endphp

            <a href="{{ $dashboardRoute }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">
                <span class="site-title">CodeVision</span>
            </a>
        </div>

        @if(Auth::check())
            <div class="user-menu-container">
                <div id="user-toggle" class="user-name">
                    {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}
                </div>
            </div>
        @else
            <div class="user-menu-container">
                <a href="{{ route('login') }}" style="text-decoration: none; color: inherit; font-weight: bold;">Iniciar Sesión</a>
            </div>
        @endif
    </nav>

    <div class="event">
        <div class="form-container">
            <h2>{{ $event->title }}</h2>

            <div class="step active">
                <div class="form-row">
                    <div class="form-group">
                        <label>Organizador</label>
                        <input type="text" value="{{ $event->organizer }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Categoría</label>
                        <input type="text" value="{{ $event->main_category }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Modalidad</label>
                        <input type="text" value="{{ $event->modality }}" disabled>
                    </div>
                </div>

                <label>Descripción</label>
                <textarea disabled>{{ $event->description }}</textarea>

                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input type="text" value="{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Hora</label>
                        <input type="text" value="{{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} hrs" disabled>
                    </div>
                    <div class="form-group">
                        <label>Lugar</label>
                        <input type="text" value="{{ $event->location }}" disabled>
                    </div>
                </div>

                {{-- NUEVA SECCIÓN: Criterios de Evaluación (Rúbrica Pública) --}}
                @if($event->criteria->count() > 0)
                    <div style="margin-bottom: 20px;">
                        <label>Aspectos a Evaluar (Rúbrica)</label>
                        <ul class="criteria-list">
                            @foreach($event->criteria as $criterio)
                                <li class="criteria-tag">
                                    {{ $criterio->name }} 
                                    <span class="criteria-points">{{ $criterio->max_points }} pts</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <label>Requisitos</label>
                <textarea disabled>{{ $event->requirements ?? 'No especificados' }}</textarea>

                @if($event->documents_info)
                    <label>Información Adicional / Documentos</label>
                    <textarea disabled>{{ $event->documents_info }}</textarea>
                @endif

                @if($event->image_url)
                    <div style="margin-top: 20px; text-align: center;">
                        <label style="display:block; margin-bottom:10px;">Imagen del Evento</label>
                        <img src="{{ $event->image_url }}" alt="Imagen evento" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    </div>
                @endif
                
                {{-- Botón de Acción para Estudiantes --}}
                @if(Auth::check() && Auth::user()->role === 'student' && $event->is_active)
                    <div style="text-align: center; margin-top: 30px;">
                        <a href="{{ route('student.event.show', $event->id) }}" 
                           style="background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 1.1em;">
                           Ir al Panel del Evento →
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <footer class="footer">
        <p class="footer-copy">© {{ date('Y') }} CodeVision</p>
    </footer>

</body>
</html>