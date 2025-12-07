<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resultados: {{ $evento->name }} | CodeVision</title>
    <link rel="stylesheet" href="{{ asset('styles/equipos.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" class="logo">
            <span class="site-title">CodeVision</span>
        </div>
        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname }}
            </div>
        </div>
    </nav>

    <h1>Panel administrativo - {{ $evento->name }}</h1>

    <table id="tablaEquipos">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre del Equipo</th>
                <th>Integrantes</th> <th>Calificación Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipos as $index => $equipo)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    
                    <td>{{ $equipo->name }}</td>

                    <td>{{ $equipo->users->count() }} / {{ $equipo->max_members }}</td>

                    <td style="font-weight: bold; text-align: center;">
                        @if($equipo->evaluations->count() > 0)
                            {{ $equipo->evaluations->sum('score') }} pts
                        @else
                            <span style="color: gray; font-size: 0.9em;">Sin evaluar</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">
                        No hay equipos registrados en este evento todavía.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="admin-box" style="text-align: right; margin-top: 20px; display: flex; justify-content: space-between; padding: 0 10%;">
        
        <button class="btn-admin" onclick="window.location.href='{{ route('dashboard.admin') }}'" style="background-color: #555;">
            ← Volver al inicio
        </button>

        <button class="btn-admin" onclick="window.location.href='{{ route('editarevento') }}'">
            Ver información del evento
        </button>

    </div>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca para gestión de eventos tecnológicos.</p>
            </div>
        </div>
        <p class="footer-copy">© {{ date('Y') }} CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>
</html>
