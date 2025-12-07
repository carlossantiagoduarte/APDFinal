<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Evento</title>
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
        <h3>Reporte de Resultados: {{ $evento->title }}</h3>
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
            @foreach($equipos as $index => $equipo)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $equipo->name }}</td>
                    <td>{{ $equipo->users->count() }}</td>
                    <td>{{ $equipo->evaluations->sum('score') }} pts</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
