<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resultados: {{ $event->title }} | Admin CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/equipos.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        /* Estilos inline para asegurar compatibilidad */
        body { font-family: 'Inter', sans-serif; background-color: #f9f9f9; padding-bottom: 50px; }
        .container { max-width: 1100px; margin: 40px auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
        h1 { font-family: 'Jomolhari', serif; border-bottom: 2px solid #333; padding-bottom: 15px; margin-bottom: 30px; }
        
        .back-nav a { display: inline-flex; align-items: center; gap: 5px; color: #666; text-decoration: none; font-weight: 500; margin-bottom: 20px; }
        .back-nav a:hover { color: #000; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f0f0f0; text-align: left; padding: 15px; font-weight: 600; color: #333; }
        td { padding: 15px; border-bottom: 1px solid #eee; color: #555; }
        tr:hover { background-color: #fdfdfd; }
        
        .badge-place { display: inline-block; width: 25px; height: 25px; text-align: center; border-radius: 50%; font-weight: bold; margin-right: 5px; }
        .place-1 { background: #ffd700; color: #000; }
        .place-2 { background: #c0c0c0; color: #000; }
        .place-3 { background: #cd7f32; color: #fff; }
        
        .score { font-weight: bold; color: #2c3e50; font-size: 1.1em; }
        
        /* Botones de Admin */
        .actions-bar { margin-top: 40px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 20px; flex-wrap: wrap; gap: 10px; }
        .btn { padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; cursor: pointer; border: none; font-size: 0.9em; transition: 0.2s; display: inline-flex; align-items: center; gap: 5px; }
        
        .btn-pdf { background-color: #dc3545; color: white; }
        .btn-excel { background-color: #28a745; color: white; }
        .btn-close-event { background-color: #333; color: white; }
        
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }

        .status-badge { padding: 5px 10px; border-radius: 15px; font-size: 0.8em; font-weight: bold; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-closed { background: #fee2e2; color: #991b1b; }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            <a href="{{ route('dashboard.admin') }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo">
                <span class="site-title">CodeVision Admin</span>
            </a>
        </div>
        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} (Administrador)
            </div>
        </div>
    </nav>

    <div class="container">
        
        <div class="back-nav">
            <a href="{{ route('dashboard.admin') }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Volver al Dashboard
            </a>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Resultados: {{ $event->title }}</h1>
            
            @if($event->is_active)
                <span class="status-badge status-active">🟢 Evento Activo</span>
            @else
                <span class="status-badge status-closed">🔴 Evento Finalizado</span>
            @endif
        </div>

        {{-- LÓGICA DE ORDENAMIENTO EN TIEMPO REAL --}}
        @php
            // Ordenamos los equipos por promedio (descendente)
            // Si el promedio es null, se trata como 0
            $equiposOrdenados = $equipos->sortByDesc(function($equipo) {
                return $equipo->evaluations->avg('score') ?? 0;
            })->values();
        @endphp

        <table>
            <thead>
                <tr>
                    <th width="10%">Posición</th>
                    <th width="35%">Equipo</th>
                    <th width="20%">Integrantes</th>
                    <th width="15%">Evaluaciones</th>
                    <th width="20%">Promedio Actual</th>
                    <th width="10%">Acciones</th>
                </tr>
            </thead>
            <tbody>
    @forelse($equiposOrdenados as $index => $equipo)
        @php 
            $promedio = $equipo->evaluations->avg('score'); 
            // Usamos null si no hay promedio para evitar el string '-' en la comparación del if
            $rank = $promedio ? ($index + 1) : null;
            $juecesCount = $equipo->evaluations->unique('user_id')->count();
        @endphp
        <tr>
            {{-- Columna 1: Posición/Medallas --}}
            <td>
                @if($promedio)
                    @if($rank == 1) <span class="badge-place place-1">1</span> 🥇
                    @elseif($rank == 2) <span class="badge-place place-2">2</span> 🥈
                    @elseif($rank == 3) <span class="badge-place place-3">3</span> 🥉
                    @else <span style="color:#999; margin-left:10px;">{{ $rank }}</span>
                    @endif
                @else
                    <span style="color:#999; margin-left:10px;">-</span>
                @endif
            </td>
            {{-- Columna 2: Equipo --}}
            <td>
                <strong style="color: #333; font-size: 1.05em;">{{ $equipo->name }}</strong>
                <div style="font-size:0.85em; color:#777;">Líder: {{ $equipo->leader_name }}</div>
            </td>
            {{-- Columna 3: Integrantes --}}
            <td>{{ $equipo->users->count() }}</td>
            {{-- Columna 4: Evaluaciones --}}
            <td>
                @if($juecesCount > 0)
                    {{ $juecesCount }} Jueces
                @else
                    <span style="color:#999;">-</span>
                @endif
            </td>
            {{-- Columna 5: Promedio Actual --}}
            <td>
                @if($promedio)
                    <span class="score">{{ number_format($promedio, 1) }}</span> / 100
                @else
                    <span style="color: #999; font-style: italic;">Sin calificar</span>
                @endif
            </td>
            
            {{-- COLUMNA 6: ACCIONES (ELIMINAR) - CORRECTO --}}
            <td>
                <form action="{{ route('teams.destroy', $equipo->id) }}" method="POST"
                    onsubmit="return confirm('¿Está seguro de que desea ELIMINAR al equipo {{ $equipo->name }}? Esta acción es irreversible.');">
                    @csrf
                    @method('delete')
                    <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 5px; font-weight: 600; font-size: 0.9em;">
                        🗑️ Eliminar
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            {{-- Colspan debe ser 6 para cubrir todas las columnas --}}
            <td colspan="6" style="text-align: center; padding: 40px; color: #888;">
                No hay equipos registrados en este evento.
            </td>
        </tr>
    @endforelse
</tbody>
        </table>

        <div class="actions-bar">
            
            <div style="display: flex; gap: 10px;">
                {{-- Botón PDF --}}
                <a href="{{ route('events.pdf', $event->id) }}" class="btn btn-pdf" target="_blank">
                    📄 Reporte PDF
                </a>
                
                {{-- Botón Excel --}}
                <a href="{{ route('events.excel', $event->id) }}" class="btn btn-excel">
                    📊 Exportar Excel
                </a>

                {{-- Botón para Ver y Editar Evento (Agregado) --}}
<a href="{{ route('events.edit', $event->id) }}" class="btn" style="background-color: #555; color: white;">
    ✏️ Ver y Editar Evento
</a>
            </div>

            {{-- Botón para Cerrar Evento y Definir Ganadores --}}
            @if($event->is_active && $equipos->count() > 0)
                <form action="{{ route('events.setWinners', $event->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Esto cerrará el evento y asignará los lugares 1, 2 y 3 permanentemente.');">
                    @csrf
                    <button type="submit" class="btn btn-close-event">
                        🏆 Determinar Ganadores y Cerrar Evento
                    </button>
                </form>
            @endif

        </div>

    </div>

    <footer class="footer">
        <p class="footer-copy">© {{ date('Y') }} CodeVision</p>
    </footer>

</body>
</html>