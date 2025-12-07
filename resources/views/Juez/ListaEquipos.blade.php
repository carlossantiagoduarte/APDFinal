<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Equipos: {{ $evento->title }} | CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/equipos.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        /* Estilos para badges de estado */
        .badge { padding: 5px 10px; border-radius: 15px; font-size: 0.85em; font-weight: bold; }
        .badge-pending { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .badge-graded { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        
        /* Botón de acción principal */
        .btn-action { 
            display: inline-block; 
            background-color: #333; 
            color: white; 
            padding: 8px 15px; 
            border-radius: 5px; 
            text-decoration: none; 
            font-size: 0.9em; 
            transition: 0.2s; 
        }
        .btn-action:hover { background-color: #555; }

        .back-link { 
            text-decoration: none; color: #333; display: inline-flex; 
            align-items: center; gap: 5px; margin: 20px; font-weight: bold; 
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="navbar-left">
            <span class="site-title">Panel de Evaluación</span>
        </div>
        <div class="user-menu-container">
            <div class="user-name">{{ Auth::user()->name }}</div>
        </div>
    </nav>

    <a href="{{ route('dashboard.juez') }}" class="back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Volver a Eventos
    </a>

    <h1 style="margin-left: 20px;">Equipos en: {{ $evento->title }}</h1>

    <table id="tablaEquipos">
        <thead>
            <tr>
                <th style="width: 30%;">Equipo</th>
                <th style="width: 25%;">Proyecto</th>
                <th style="width: 20%;">Estado Entrega</th>
                <th style="width: 15%;">Tu Evaluación</th>
                <th style="width: 10%;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipos as $equipo)
                <tr>
                    <td>
                        <strong style="font-size: 1.1em;">{{ $equipo->name }}</strong><br>
                        <span style="font-size: 0.9em; color: #666;">Líder: {{ $equipo->leader_name }}</span>
                    </td>

                    <td>
                        {{ $equipo->project_name ?? 'Sin nombre asignado' }}
                        
                    </td>
                    
                    <td>
                        @if($equipo->project_file_path)
                            <span style="color: green;">✅ Archivo Entregado</span>
                        @else
                            <span style="color: #d9534f;">⚠️ Sin entregar</span>
                        @endif
                    </td>
                    
                    <td>
                        @php 
                            $miNota = $equipo->evaluations->first(); 
                        @endphp

                        @if($miNota)
                            <span class="badge badge-graded">Calificado: {{ $miNota->score }}/100</span>
                        @else
                            <span class="badge badge-pending">Pendiente</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('juez.equipo.detalle', $equipo->id) }}" class="btn-action">
                            @if($miNota) ✏️ Editar @else 👁️ Evaluar @endif
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #666;">
                        No hay equipos registrados en este evento.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="text-align: center; margin-top: 40px; margin-bottom: 40px; padding: 20px; background-color: #f9f9f9; border-top: 1px solid #eee;">
        <h3 style="color: #555; margin-bottom: 15px;">¿Necesitas revisar las bases del concurso?</h3>
        <a href="{{ route('events.show', $evento->id) }}" 
           style="display: inline-block; text-decoration: none; background-color: #333; color: white; padding: 12px 25px; border-radius: 5px; font-weight: bold; transition: 0.3s;">
            ℹ️ Ver Información Completa del Evento
        </a>
    </div>

</body>
</html>
