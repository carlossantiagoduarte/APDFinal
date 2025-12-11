<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $event->title }} | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('styles/verevento.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Estilos existentes... */
        .team-management { margin-top: 20px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: left; color: #333333 !important; }
        .team-management h3 { color: #111; margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .member-list { list-style: none; padding: 0; margin: 0; }
        .member-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed #e0e0e0; font-size: 0.95em; color: #333; }
        .member-item:last-child { border-bottom: none; }
        .member-info { display: flex; align-items: center; gap: 10px; }
        .btn-kick { background-color: #ff4d4d; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.8em; font-weight: bold; }
        .btn-leave { background-color: #ff9800; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 15px; border: none; cursor: pointer; font-weight: bold; }
        .btn-disabled { background-color: #ccc !important; color: #666 !important; cursor: not-allowed !important; pointer-events: none; }

        /* NUEVOS ESTILOS PARA EL PODIO */
        .podium-container {
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
        }
        .podium-title {
            font-family: 'Jomolhari', serif;
            font-size: 1.8rem;
            color: #1f2937;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .podium-places {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 20px;
            flex-wrap: wrap;
        }
        .podium-card {
            background: #f3f4f6;
            border-radius: 10px 10px 0 0;
            padding: 20px;
            width: 160px;
            text-align: center;
            position: relative;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .podium-1 { background-color: #fef3c7; border: 2px solid #fbbf24; height: 220px; order: 2; z-index: 2; } /* Oro */
        .podium-2 { background-color: #f3f4f6; border: 2px solid #9ca3af; height: 180px; order: 1; } /* Plata */
        .podium-3 { background-color: #fff7ed; border: 2px solid #fdba74; height: 150px; order: 3; } /* Bronce */
        
        .medal-icon { font-size: 3rem; display: block; margin-bottom: 10px; }
        .podium-team-name { font-weight: bold; font-size: 1.1rem; color: #111; display: block; margin-bottom: 5px; }
        .podium-score { font-size: 0.9rem; color: #666; font-weight: 600; }
        .podium-rank { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; margin-top: 10px; display: block; font-weight: bold; opacity: 0.7; }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            <a href="{{ route('dashboard.estudiante') }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo">
                <span class="site-title">CodeVision</span>
            </a>
        </div>
        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}
            </div>
        </div>
    </nav>

    <div class="container">

        <div class="back-nav">
            <a href="{{ route('dashboard.estudiante') }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Volver al Dashboard
            </a>
        </div>

        @if(session('success')) <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;">{{ session('success') }}</div> @endif
        @if($errors->any()) <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;">{{ $errors->first() }}</div> @endif

        <div class="header-card">
            <div>
                <h1 class="event-title">{{ $event->title }}</h1>
                <div class="event-meta">
                    <span>📍 {{ $event->location }}</span>
                    <span>📅 {{ \Carbon\Carbon::parse($event->start_date)->format('d M, Y') }}</span>
                    <span>👥 Equipos: {{ $teams->count() }} / Máx: {{ $event->max_participants }}</span>
                </div>
            </div>
            <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                {{-- Lógica de Estado: Si admin cerró (is_active false) o si ya pasó fecha --}}
                @if (!$event->is_active)
                    <span class="status-badge status-closed" style="background:#dc2626; color:white;">🏆 Evento Finalizado</span>
                @elseif ($yaInicio)
                    <span class="status-badge status-closed">En Progreso</span>
                @else
                    <span class="status-badge status-open">Inscripciones Abiertas</span>
                @endif
                <a href="{{ route('events.show', $event->id) }}" class="btn-info">Ver Bases y Reglas ℹ️</a>
            </div>
        </div>

        {{-- LÓGICA DE PODIO: Solo si el evento está CERRADO (!is_active) --}}
        @if(!$event->is_active)
            @php
                // Ordenar equipos por promedio de calificaciones (Descendente)
                $topEquipos = $teams->sortByDesc(function($t) {
                    return $t->evaluations->avg('score') ?? 0;
                })->values()->take(3); // Tomamos solo los 3 mejores
            @endphp

            @if($topEquipos->count() > 0)
                <div class="podium-container">
                    <h2 class="podium-title">🏆 Ganadores del Evento 🏆</h2>
                    
                    <div class="podium-places">
                        {{-- 2do Lugar (Izquierda) --}}
                        @if(isset($topEquipos[1]))
                            <div class="podium-card podium-2">
                                <span class="medal-icon">🥈</span>
                                <span class="podium-team-name">{{ $topEquipos[1]->name }}</span>
                                <span class="podium-score">{{ number_format($topEquipos[1]->evaluations->avg('score'), 1) }} pts</span>
                                <span class="podium-rank">2do Lugar</span>
                            </div>
                        @endif

                        {{-- 1er Lugar (Centro) --}}
                        @if(isset($topEquipos[0]))
                            <div class="podium-card podium-1">
                                <span class="medal-icon">🥇</span>
                                <span class="podium-team-name">{{ $topEquipos[0]->name }}</span>
                                <span class="podium-score">{{ number_format($topEquipos[0]->evaluations->avg('score'), 1) }} pts</span>
                                <span class="podium-rank">1er Lugar</span>
                            </div>
                        @endif

                        {{-- 3er Lugar (Derecha) --}}
                        @if(isset($topEquipos[2]))
                            <div class="podium-card podium-3">
                                <span class="medal-icon">🥉</span>
                                <span class="podium-team-name">{{ $topEquipos[2]->name }}</span>
                                <span class="podium-score">{{ number_format($topEquipos[2]->evaluations->avg('score'), 1) }} pts</span>
                                <span class="podium-rank">3er Lugar</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif

        <div class="action-box">
            @if ($miEquipo)
                <h2>Tu Equipo: <strong>{{ $miEquipo->name }}</strong></h2>
                <p>Código de invitación: <strong>{{ $miEquipo->invite_code }}</strong></p>

                <div class="team-management">
                    <h3>Integrantes del Equipo</h3>
                    <ul class="member-list">
                        @foreach($miEquipo->users as $miembro)
                            <li class="member-item">
                                <div class="member-info">
                                    <span>👤 {{ $miembro->name }} {{ $miembro->lastname }}</span>
                                    @if($miembro->pivot->role === 'leader') 
                                        <span style="background:gold; padding:2px 6px; border-radius:4px; font-size:0.8em; font-weight:bold; color:black;">LÍDER</span> 
                                    @endif
                                </div>

                                {{-- Botón SACAR: Solo líder, si no ha iniciado, y no a sí mismo --}}
                                @php
                                    $soyLider = $miEquipo->users()->where('user_id', Auth::id())->wherePivot('role', 'leader')->exists();
                                @endphp

                                @if($soyLider && !$yaInicio && $miembro->id !== Auth::id())
                                    <form action="{{ route('teams.remove_member', $miEquipo->id) }}" method="POST" onsubmit="return confirm('¿Sacar a este miembro?');">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $miembro->id }}">
                                        <button type="submit" class="btn-kick">Expulsar</button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    @if(!$soyLider && !$yaInicio)
                        <form action="{{ route('teams.leave', $miEquipo->id) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres salirte?');">
                            @csrf
                            <button type="submit" class="btn-leave">Abandonar Equipo</button>
                        </form>
                    @endif
                </div>

                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px;">
                    @if($event->is_active)
                        <a href="{{ route('delivery.view', $miEquipo->id) }}" class="btn-action btn-upload">
                            📁 Gestión de Proyecto
                        </a>
                    @else
                        <button class="btn-action btn-disabled">📁 Entrega Cerrada</button>
                    @endif

                    @php
                        $totalJueces = $event->jueces->count();
                        $juecesCalificaron = $miEquipo->evaluations->unique('user_id')->count();
                        // Permitir descargar SI ya calificaron todos O el evento está cerrado y tiene calificación
                        $tieneNota = $juecesCalificaron > 0;
                        $listo = ($totalJueces > 0 && $juecesCalificaron >= $totalJueces) || (!$event->is_active && $tieneNota);
                    @endphp

                    @if($listo)
                        <a href="{{ route('student.certificate', $event->id) }}" class="btn-action" style="background-color: #eab308; color: black;">
                            🎓 Descargar Constancia
                        </a>
                    @else
                        <button class="btn-action btn-disabled" title="Faltan calificaciones">
                            ⏳ Constancia Pendiente
                        </button>
                    @endif
                </div>
            
            @elseif ($yaInicio || !$event->is_active)
                <h2>Inscripciones Cerradas</h2>
                <p>El evento ha comenzado o finalizado. Puedes consultar los resultados.</p>
                <button class="btn-action btn-secondary" style="opacity: 0.5; cursor: not-allowed;">Cerrado</button>
            @else
                <h2>¿Listo para participar?</h2>
                <p>Crea o únete a un equipo.</p>
                <div style="display: flex; justify-content: center; gap: 15px;">
                    <a href="{{ route('teams.create', ['event_id' => $event->id]) }}" class="btn-action btn-primary">➕ Crear Equipo</a>
                    <a href="{{ route('teams.join.view', ['event_id' => $event->id]) }}" class="btn-action btn-secondary">🔍 Unirse a Equipo</a>
                </div>
            @endif
        </div>

        <div class="section-title">
            <span>Listado de Equipos</span>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr><th>Nombre</th><th>Líder</th><th>Miembros</th><th>Estado</th></tr>
                </thead>
                <tbody>
                    @forelse($teams as $team)
                        <tr class="{{ $miEquipo && $miEquipo->id == $team->id ? 'my-team-row' : '' }}">
                            <td>
                                <strong>{{ $team->name }}</strong>
                                @if ($miEquipo && $miEquipo->id == $team->id) <span style="color:#3b82f6; font-weight:bold;">(TÚ)</span> @endif
                            </td>
                            <td>{{ $team->leader_name }}</td>
                            <td>{{ $team->users->count() }}</td>
                            <td>
                                @if ($team->project_file_path) <span class="badge-status badge-done">✅ Enviado</span>
                                @else <span class="badge-status badge-pending">⏳ Pendiente</span> @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; padding: 30px;">Aún no hay equipos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer-simple"><p>© {{ date('Y') }} CodeVision</p></div>
</body>
</html>