<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Equipos: {{ $event->title }} | CodeVision</title>

    <link rel="stylesheet" href="{{ asset('styles/equipos.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
         <div class="navbar-left">
             <a href="{{ route('dashboard.juez') }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo">
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
        </div>
    </nav>

    <a href="{{ route('dashboard.juez') }}" class="back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Volver a Eventos
    </a>

    <h1 style="margin-left: 20px;">Equipos en: {{ $event->title }}</h1>

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

                    <td>{{ $equipo->project_name ?? 'Sin nombre asignado' }}</td>

                    <td>
                        @if ($equipo->project_file_path)
                            <span style="color: green;">✅ Archivo Entregado</span>
                        @else
                            <span style="color: #d9534f;">⚠️ Sin entregar</span>
                        @endif
                    </td>

                    <td>
                        @php
                            $miNota = $equipo->evaluations->first();
                        @endphp

                        @if ($miNota)
                            <span class="badge badge-graded" style="background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 15px; font-size: 0.9em;">
                                Calificado: {{ $miNota->score }}/100
                            </span>
                        @else
                            <span class="badge badge-pending" style="background-color: #fff3cd; color: #856404; padding: 5px 10px; border-radius: 15px; font-size: 0.9em;">
                                Pendiente
                            </span>
                        @endif
                    </td>

                    <td>
                        {{-- CAMBIO AQUÍ: Lógica de Botones --}}
                        @if ($miNota)
                            {{-- Si ya calificó, mostramos texto estático o botón deshabilitado --}}
                            <span style="color: #888; font-weight: bold; font-size: 0.9em; cursor: default;">
                                ✅ Enviado
                            </span>
                        @else
                            {{-- Si NO ha calificado, mostramos el botón de Evaluar --}}
                            <a href="{{ route('judge.team.details', $equipo->id) }}" class="btn-action" 
                               style="background-color: #007bff; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; font-size: 0.9em;">
                                👁️ Evaluar
                            </a>
                        @endif
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
    
    @if (isset($ganadores) && $ganadores->isNotEmpty())
        <section class="podium-section"
            style="margin-bottom: 40px; text-align: center; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h2 style="font-family: 'Jomolhari', serif; color: #1a1a1a; margin-bottom: 20px;">
                🥇 PODIO OFICIAL 🥈
            </h2>

            <div style="display: flex; justify-content: center; gap: 30px; align-items: flex-end;">

                @if (isset($ganadores[1]))
                    <div style="width: 150px; height: 180px; background-color: #c0c0c0; border-radius: 8px 8px 0 0; padding-top: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                        <div style="font-size: 2.5rem;">🥈</div>
                        <p style="font-weight: bold; margin: 5px 0;">{{ $ganadores[1]->name }}</p>
                        <small style="color: #444;">2do Lugar</small>
                    </div>
                @endif

                @if (isset($ganadores[0]))
                    <div style="width: 170px; height: 220px; background-color: #ffd700; border-radius: 8px 8px 0 0; padding-top: 10px; position: relative; top: -20px; box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
                        <div style="font-size: 3rem;">🥇</div>
                        <p style="font-weight: bold; margin: 10px 0;">{{ $ganadores[0]->name }}</p>
                        <small style="color: #444;">1er Lugar</small>
                    </div>
                @endif

                @if (isset($ganadores[2]))
                    <div style="width: 150px; height: 150px; background-color: #cd7f32; border-radius: 8px 8px 0 0; padding-top: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
                        <div style="font-size: 2.5rem;">🥉</div>
                        <p style="font-weight: bold; margin: 5px 0;">{{ $ganadores[2]->name }}</p>
                        <small style="color: #444;">3er Lugar</small>
                    </div>
                @endif
            </div>
        </section>
    @endif
    
    <div style="text-align: center; margin-top: 40px; margin-bottom: 40px; padding: 20px; background-color: #f9f9f9; border-top: 1px solid #eee;">
        <h3 style="color: #555; margin-bottom: 15px;">¿Necesitas revisar las bases del concurso?</h3>
        <a href="{{ route('events.show', $event->id) }}" 
            style="display: inline-block; text-decoration: none; background-color: #333; color: white; padding: 12px 25px; border-radius: 5px; font-weight: bold; transition: 0.3s;">
            ℹ️ Ver Información Completa del Evento
        </a>
    </div>

</body>
</html>