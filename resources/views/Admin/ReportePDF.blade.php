<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Evento</title>
    <link rel="stylesheet" href="{{ asset('styles/reportes.css') }}">
    <style>
        body { font-family: sans-serif; }
        h1 { text-align: center; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Instituto Tecnológico de Oaxaca</h2>
        <h3>Reporte de Resultados: {{ $event->title }}</h3>
        <p>Fecha: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Lugar</th>
                <th>Equipo</th>
                <th>Integrantes</th>
                <th>Puntaje</th>
            </tr>
        </thead>
        <tbody>
            {{-- CORREGIDO: Usar $teams y la variable de iteración $team --}}
            @foreach($teams as $index => $team)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $team->name }}</td>
                    {{-- Usar $team en lugar de $equipo --}}
                    <td>{{ $team->users->count() }}</td> 
                    <td>{{ $team->evaluations->avg('score') }} pts</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>