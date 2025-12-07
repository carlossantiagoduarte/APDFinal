<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluar: {{ $evento->title }}</title>
    
    <link rel="stylesheet" href="{{ asset('styles/equipos.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        /* Estilos específicos para esta vista */
        .score-input { 
            width: 70px; 
            padding: 8px; 
            text-align: center; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            font-weight: bold; 
        }
        .score-input:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        
        .feedback-input { 
            width: 100%; 
            padding: 8px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            margin-top: 5px; 
        }
        
        .btn-save { 
            background-color: #28a745; 
            color: white; 
            border: none; 
            padding: 8px 12px; 
            border-radius: 5px; 
            cursor: pointer; 
            transition: 0.3s; 
        }
        .btn-save:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .btn-save:hover:not(:disabled) { 
            background-color: #218838; 
        }

        .btn-download {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9em;
            margin-bottom: 5px;
            cursor: pointer;
        }
        .btn-download.downloaded {
            background-color: #6c757d; /* Gris cuando ya se descargó */
            cursor: default;
        }

        .back-link { 
            text-decoration: none; 
            color: #333; 
            display: inline-flex; 
            align-items: center; 
            gap: 5px; 
            margin: 20px; 
            font-weight: bold; 
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

    <a href="{{ route('dashboard.juez') }}" class="back-link">← Volver a Eventos</a>

    <h1 style="margin-left: 20px;">Evaluando: {{ $evento->title }}</h1>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; margin: 20px; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    <table id="tablaEquipos">
        <thead>
            <tr>
                <th style="width: 25%;">Equipo y Proyecto</th>
                <th style="width: 20%;">Archivo</th>
                <th style="width: 15%;">Calificación (0-100)</th>
                <th style="width: 30%;">Retroalimentación</th>
                <th style="width: 10%;">Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipos as $equipo)
                <tr>
                    <td>
                        <strong style="font-size: 1.1em;">{{ $equipo->name }}</strong><br>
                        <span style="font-size: 0.9em; color: #666;">Líder: {{ $equipo->leader_name }}</span>
                        @if($equipo->project_name)
                            <br><div style="margin-top:5px; font-style:italic;">"{{ $equipo->project_name }}"</div>
                        @endif
                    </td>

                    <td>
                        <a href="#" 
                           class="btn-download" 
                           id="btn-download-{{ $equipo->id }}"
                           onclick="enableGrading({{ $equipo->id }}); return false;">
                            📄 Descargar Proyecto
                        </a>
                        <br>
                        <small style="color: #888;" id="status-{{ $equipo->id }}">Descarga para calificar</small>
                    </td>
                    
                    <form action="{{ route('juez.calificar', $equipo->id) }}" method="POST">
                        @csrf
                        
                        @php 
                            $nota = $equipo->evaluations->first(); 
                            $yaCalificado = $nota ? true : false;
                        @endphp

                        <td>
                            <input type="number" 
                                   name="score" 
                                   id="score-{{ $equipo->id }}"
                                   class="score-input" 
                                   min="0" max="100" 
                                   required 
                                   value="{{ $nota ? $nota->score : '' }}" 
                                   placeholder="-"
                                   {{ $yaCalificado ? '' : 'disabled' }}> 
                        </td>

                        <td>
                            <textarea name="feedback" 
                                      id="feedback-{{ $equipo->id }}"
                                      class="feedback-input" 
                                      rows="2"
                                      placeholder="Comentario para el equipo..." 
                                      {{ $yaCalificado ? '' : 'disabled' }}>{{ $nota ? $nota->feedback : '' }}</textarea>
                        </td>

                        <td>
                            <button type="submit" 
                                    id="btn-save-{{ $equipo->id }}"
                                    class="btn-save"
                                    {{ $yaCalificado ? '' : 'disabled' }}>
                                {{ $nota ? 'Actualizar' : 'Guardar' }}
                            </button>
                        </td>
                    </form>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px; color: #666;">
                        No hay equipos registrados en este evento para calificar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        function enableGrading(equipoId) {
            // 1. Cambiar apariencia del botón de descarga
            const downloadBtn = document.getElementById('btn-download-' + equipoId);
            const statusText = document.getElementById('status-' + equipoId);
            
            downloadBtn.innerHTML = "✅ Descargado";
            downloadBtn.classList.add('downloaded');
            statusText.innerText = "Listo para calificar";
            statusText.style.color = "green";

            // 2. Desbloquear los inputs
            document.getElementById('score-' + equipoId).disabled = false;
            document.getElementById('feedback-' + equipoId).disabled = false;
            document.getElementById('btn-save-' + equipoId).disabled = false;

            // 3. (Opcional) Simular descarga real o redirigir al archivo
            // window.location.href = '/ruta/al/archivo/real/' + equipoId; 
            
            alert("Archivo descargado. Ahora puedes calificar al equipo.");
        }
    </script>

</body>
</html>
