<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $event->title }} | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('styles/dashboard.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* --- ESTILOS GENERALES Y LIMPIOS --- */
        body {
            background-color: #f4f6f9;
            font-family: 'Inter', sans-serif;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Navegación de regreso */
        .back-nav {
            margin-bottom: 25px;
        }

        .back-nav a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.2s;
        }

        .back-nav a:hover {
            color: #000;
        }

        /* Encabezado del Evento */
        .header-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border: 1px solid #eee;
        }

        .event-title {
            font-family: 'Jomolhari', serif;
            font-size: 2.2rem;
            margin: 0 0 10px 0;
            color: #1a1a1a;
        }

        .event-meta {
            display: flex;
            gap: 20px;
            color: #666;
            font-size: 0.95rem;
            align-items: center;
        }

        .event-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Badges de Estado */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-open {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status-closed {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Botón Info */
        .btn-info {
            background: white;
            color: #333;
            border: 1px solid #ccc;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .btn-info:hover {
            background: #f8f8f8;
            border-color: #999;
        }

        /* --- ZONA DE ACCIÓN (Action Box) --- */
        .action-box {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            border-radius: 12px;
            padding: 35px;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .action-box h2 {
            margin: 0 0 10px 0;
            font-family: 'Jomolhari', serif;
            font-size: 1.8rem;
        }

        .action-box p {
            color: #cbd5e1;
            margin-bottom: 25px;
            font-size: 1rem;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Colores de botones */
        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        /* Azul */
        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Transparente */
        .btn-success {
            background-color: #10b981;
            color: white;
        }

        /* Verde */
        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* --- TABLA DE EQUIPOS --- */
        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #eee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #333;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        /* Fila destacada (Mi equipo) */
        .my-team-row {
            background-color: #eff6ff !important;
        }

        .my-team-row td:first-child {
            border-left: 4px solid #3b82f6;
        }

        .badge-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .badge-pending {
            background-color: #fffbeb;
            color: #b45309;
        }

        .badge-done {
            background-color: #ecfdf5;
            color: #047857;
        }

        /* Footer simple */
        .footer-simple {
            text-align: center;
            margin-top: 50px;
            color: #999;
            font-size: 0.9rem;
        }
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

    <div class="container">

        <div class="back-nav">
            <a href="{{ route('dashboard.estudiante') }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Volver al Dashboard
            </a>
        </div>

        <div class="header-card">
            <div>
                <h1 class="event-title">{{ $event->title }}</h1>
                <div class="event-meta">
                    <span>📍 {{ $event->location }}</span>
                    <span>📅 {{ \Carbon\Carbon::parse($event->start_date)->format('d M, Y') }}</span>
                    <span>👥 Capacidad: {{ $teams->count() * 5 }} / {{ $event->max_participants }}</span>
                </div>
            </div>
            <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                @if ($yaInicio)
                    <span class="status-badge status-closed">Inscripciones Cerradas</span>
                @else
                    <span class="status-badge status-open">Inscripciones Abiertas</span>
                @endif
                <a href="{{ route('events.show', $event->id) }}" class="btn-info">Ver Bases y Reglas ℹ️</a>
            </div>
        </div>

        <div class="action-box">

            @if ($miEquipo)
                <h2>Eres parte del equipo "{{ $miEquipo->name }}"</h2>
                <p>Tu equipo ya está registrado en este evento. Accede al panel de entrega para gestionar tu proyecto.
                </p>
                <a href="{{ route('entrega.proyecto', $miEquipo->id) }}" class="btn-action btn-upload">
                Ir a Entregar Proyecto
                </a>
            @elseif($yaInicio)
                <h2>El evento ha comenzado</h2>
                <p>Lo sentimos, el periodo de inscripción ha finalizado. Puedes ver la lista de participantes abajo.</p>
                <button class="btn-action btn-secondary" style="opacity: 0.5; cursor: not-allowed;">Inscripción
                    Cerrada</button>
            @else
                <h2>¿Listo para participar?</h2>
                <p>Aún no tienes equipo en este evento. Crea uno nuevo o únete a uno existente.</p>
                <div style="display: flex; justify-content: center; gap: 15px;">
                    <a href="{{ route('crearequipo', ['event_id' => $event->id]) }}" class="btn-action btn-primary">
                        ➕ Crear Equipo
                    </a>
                    <a href="{{ route('unirseaequipo') }}" class="btn-action btn-secondary">
                        🔍 Unirse a Equipo
                    </a>
                </div>
            @endif

        </div>

        <div class="section-title">
            <span>Equipos Participantes ({{ $teams->count() }})</span>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre del Equipo</th>
                        <th>Líder</th>
                        <th>Miembros</th>
                        <th>Estado Proyecto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teams as $team)
                        <tr class="{{ $miEquipo && $miEquipo->id == $team->id ? 'my-team-row' : '' }}">
                            <td>
                                <strong>{{ $team->name }}</strong>
                                @if ($miEquipo && $miEquipo->id == $team->id)
                                    <span
                                        style="font-size:0.75rem; color:#3b82f6; margin-left:5px; font-weight:bold;">(TU
                                        EQUIPO)</span>
                                @endif
                            </td>
                            <td>{{ $team->leader_name }}</td>
                            <td>{{ $team->users->count() }} / {{ $team->max_members }}</td>
                            <td>
                                @if ($team->project_file_path)
                                    <span class="badge-status badge-done">✅ Entregado</span>
                                @else
                                    <span class="badge-status badge-pending">⏳ Pendiente</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #888;">
                                Aún no hay equipos registrados. ¡Sé el primero en inscribirte!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="footer-simple">
        <p>© {{ date('Y') }} CodeVision - Plataforma de Eventos</p>
    </div>

</body>

</html>
