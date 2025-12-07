<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Evaluar: {{ $equipo->name }} | CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/calificar.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" class="logo">
            <span class="site-title">CodeVision (Juez)</span>
        </div>
        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">{{ Auth::user()->name }}</div>
        </div>
    </nav>

    <div class="container">
        {{-- Mensajes de Éxito o Error --}}
        @if(session('success'))
            <div style="background-color:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom: 15px; text-align: center;">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div style="background-color:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom: 15px; text-align: center;">
                {{ $errors->first() }}
            </div>
        @endif
        
        <h2>Evaluación del equipo</h2>
        <h2>{{ $equipo->event->title }}</h2>

        <div class="card">
            <p><strong>Equipo:</strong> {{ $equipo->name }}</p>
            <p><strong>Número de integrantes:</strong> {{ $equipo->users->count() }} / {{ $equipo->max_members }}</p>

            <h4>Integrantes</h4>
            <ul class="members">
                @foreach($equipo->users as $miembro)
                    <li>
                        {{ $miembro->name }} {{ $miembro->lastname }}
                        @if($miembro->pivot->role === 'leader') 
                            <span style="color: goldenrod; font-weight: bold;">(Líder)</span> 
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card">
            <p><strong>Archivo entregado:</strong></p>

            @if($equipo->project_file_path)
                {{-- RUTA CORREGIDA: equipos.descargar -> events.download --}}
                <a id="downloadBtn" 
                   href="{{ route('events.download', $equipo->id) }}" 
                   class="btn download" 
                   target="_blank"> Descargar archivo del equipo
                </a>
                <p id="downloadMsg" style="color: green; display: none; margin-top: 5px;">✅ Archivo descargado</p>
            @else
                <button class="btn download" style="background-color: #ccc; cursor: not-allowed;" disabled>
                    ⚠️ No han subido archivo
                </button>
            @endif
        </div>

        <div class="card">
            {{-- RUTA CORREGIDA: juez.calificar -> judge.score --}}
            <form action="{{ route('judge.score', $equipo->id) }}" method="POST" id="gradingForm">
                @csrf
                
                <label>Calificación (0 - 100)</label>
                <input type="number" name="score" id="score" 
                        min="0" max="100" placeholder="Ej. 85" required
                        value="{{ $miEvaluacion ? $miEvaluacion->score : '' }}"
                        disabled>

                <label style="margin-top: 15px; display: block;">Retroalimentación (Opcional)</label>
                <textarea name="feedback" id="feedback" rows="3" 
                          style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;"
                          placeholder="Comentarios para el equipo..."
                          disabled>{{ $miEvaluacion ? $miEvaluacion->feedback : '' }}</textarea>

                <div class="buttons">
                    {{-- El disabled se manejará completamente en JS --}}
                    <button type="button" id="editBtn" class="btn edit">Habilitar Calificación</button>
                    
                    <button type="submit" id="saveBtn" class="btn save" disabled>Guardar Evaluación</button>
                    
                    <button type="button" class="btn cancel" onclick="window.location='{{ route('judge.teams', $equipo->event_id) }}'">Regresar</button>
                </div>
            </form>
        </div>

    </div>

    <footer class="footer">
        <p class="footer-copy">© {{ date('Y') }} CodeVision</p>
    </footer>

    <script>
        const downloadBtn = document.getElementById("downloadBtn");
        const editBtn = document.getElementById("editBtn");
        const saveBtn = document.getElementById("saveBtn");
        const scoreInput = document.getElementById("score");
        const feedbackInput = document.getElementById("feedback");
        const downloadMsg = document.getElementById("downloadMsg");

        // Variables PHP pasadas a JS
        const alreadyScored = @json($miEvaluacion ? true : false);
        const hasFile = @json($equipo->project_file_path ? true : false);
        
        // Función para Habilitar campos de calificación
        const enableGrading = () => {
            scoreInput.disabled = false;
            feedbackInput.disabled = false;
            saveBtn.disabled = false;
            scoreInput.focus();
            editBtn.style.display = 'none'; // Ocultamos el botón Habilitar
        };

        // --- Lógica de Inicialización ---
        
        // 1. Si ya se calificó, permitimos editar directamente y habilitamos el botón de edición
        if (alreadyScored) {
            editBtn.innerText = "Editar Calificación";
            editBtn.disabled = false; 
            
        } else if (hasFile) {
            // 2. Si no ha calificado, pero hay archivo, habilitamos el botón para empezar a calificar/descargar
            editBtn.innerText = "Empezar a Calificar";
            editBtn.disabled = false; 
        } else {
             // 3. Si no hay archivo ni nota, bloqueamos el botón Habilitar
             editBtn.innerText = "Esperando Entrega";
             editBtn.disabled = true;
             editBtn.style.backgroundColor = '#ccc';
        }


        // --- Eventos ---
        
        // Botón "Habilitar/Editar"
        editBtn.addEventListener("click", enableGrading);

        // Lógica de Descarga (Si hay archivo)
        if (downloadBtn) {
            downloadBtn.addEventListener("click", () => {
                // Simulamos un pequeño delay para activar el botón
                setTimeout(() => {
                    // Si no había nota, forzamos la activación del formulario después de la descarga
                    if (!alreadyScored) {
                         enableGrading();
                    }
                    
                    // Retroalimentación visual de descarga
                    downloadMsg.style.display = "block";
                    downloadBtn.classList.add("done");
                    downloadBtn.innerHTML = "✅ Archivo descargado";
                }, 1000);
            });
        }
    </script>

</body>
</html>
