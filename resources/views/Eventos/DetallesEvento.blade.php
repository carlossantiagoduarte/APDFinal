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
        /* Ajustes para modo lectura */
        input:disabled, textarea:disabled, select:disabled {
            background-color: #f9f9f9;
            color: #333;
            border: 1px solid #ddd;
            cursor: default;
        }
        .back-link {
            display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
            color: #333; font-weight: bold; margin: 20px 0 0 40px; padding: 8px 12px;
            border-radius: 5px; transition: background 0.2s;
        }
        .back-link:hover { background-color: #f0f0f0; }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="navbar-left">
            <span class="site-title" style="margin-left: 20px;">CodeVision</span>
        </div>
        <div class="user-menu-container">
            <div class="user-name">{{ Auth::user()->name }}</div>
        </div>
    </nav>

    <a href="javascript:history.back()" class="back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Regresar
    </a>

    <section class="hero">
        <h2 class="hero-title">Información del Evento</h2>
    </section>

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

                <label>Requisitos</label>
                <textarea disabled>{{ $event->requirements ?? 'No especificados' }}</textarea>

                @if($event->documents_info)
                    <label>Información Adicional / Documentos</label>
                    <textarea disabled>{{ $event->documents_info }}</textarea>
                @endif

                @if($event->image_url)
                    <div style="margin-top: 20px; text-align: center;">
                        <label style="display:block; margin-bottom:10px;">Imagen del Evento</label>
                        <img src="{{ $event->image_url }}" alt="Imagen evento" style="max-width: 300px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
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