<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Evaluar: {{ $equipo->name }} | CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/calificar.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* Estilos específicos para la Rúbrica */
        .rubric-container {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .rubric-header {
            display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px;
        }
        .rubric-item {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 10px; border-radius: 6px; background-color: #f9f9f9;
        }
        .rubric-info { display: flex; flex-direction: column; width: 70%; }
        .rubric-title { font-weight: 600; color: #333; }
        .rubric-max { font-size: 0.85rem; color: #666; }
        .rubric-input { width: 25%; text-align: right; }
        .rubric-input input { width: 100%; max-width: 80px; padding: 8px; text-align: center; border: 1px solid #ccc; border-radius: 5px; font-weight: bold; }
        .total-display { text-align: right; font-size: 1.3em; font-weight: bold; margin-top: 20px; color: #2c3e50; border-top: 2px solid #ccc; padding-top: 15px; }
        .total-number { color: #28a745; }
        .input-error { border-color: #dc3545 !important; background-color: #ffe6e6; }
        
        /* Botón deshabilitado visualmente */
        .btn-locked {
            background-color: #e0e0e0 !important;
            color: #999 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }
    </style>
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
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div style="background-color:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom: 15px; text-align: center;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div style="background-color:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom: 15px; text-align: center;">{{ $errors->first() }}</div>
        @endif
        
        <h2>Evaluación del equipo</h2>
        <h2 style="color: #666; margin-top: -10px;">{{ $equipo->event->title }}</h2>

        <div class="card">
            <p><strong>Equipo:</strong> {{ $equipo->name }}</p>
            <p><strong>Integrantes:</strong> {{ $equipo->users->count() }} / {{ $equipo->max_members }}</p>
            <h4>Miembros:</h4>
            <ul class="members" style="list-style: none; padding: 0;">
                @foreach($equipo->users as $miembro)
                    <li style="padding: 5px 0; border-bottom: 1px dashed #eee;">
                        {{ $miembro->name }} {{ $miembro->lastname }}
                        @if($miembro->pivot->role === 'leader') <span style="color: goldenrod; font-weight: bold;">★ Líder</span> @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card">
            <p><strong>Entregable del Proyecto:</strong></p>
            @if($equipo->project_file_path)
                {{-- Botón de descarga: Al hacer click dispara el evento para desbloquear --}}
                <a id="downloadBtn" 
                   href="{{ route('events.download', $equipo->id) }}" 
                   class="btn download" 
                   target="_blank"> 
                   📥 Descargar Archivo (Requisito para calificar)
                </a>
                <p id="downloadMsg" style="color: green; display: none; margin-top: 10px; font-weight: bold;">✅ Archivo descargado. ¡Ya puedes calificar!</p>
            @else
                <button class="btn download" style="background-color: #ccc; cursor: not-allowed;" disabled>
                    ⚠️ No han subido archivo
                </button>
            @endif
        </div>

        <div class="card">
            <form action="{{ route('judge.score', $equipo->id) }}" method="POST" id="gradingForm">
                @csrf
                
                <div class="rubric-header">
                    <h3>Rúbrica de Evaluación</h3>
                    <span style="font-size: 0.9em; color: #777;">Suma total: 100 pts</span>
                </div>

                <div class="rubric-container">
                    @forelse($rubrica as $criterio)
                        <div class="rubric-item">
                            <div class="rubric-info">
                                <span class="rubric-title">{{ $criterio->name }}</span>
                                <span class="rubric-max">Máximo: <strong>{{ $criterio->max_points }}</strong> pts</span>
                            </div>
                            <div class="rubric-input">
                                <input type="number" class="score-input" min="0" max="{{ $criterio->max_points }}"
                                    data-max="{{ $criterio->max_points }}" placeholder="0" required disabled
                                    oninput="calculateTotal(this)">
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 20px; color: #d9534f;">
                            <p>⚠️ Sin rúbrica.</p>
                            <input type="number" class="score-input" min="0" max="100" data-max="100" disabled oninput="calculateTotal(this)">
                        </div>
                    @endforelse

                    <div class="total-display">
                        Calificación Final: <span id="displayTotal" class="total-number">{{ $miEvaluacion ? $miEvaluacion->score : '0' }}</span> / 100
                    </div>
                </div>

                <input type="hidden" name="score" id="finalScore" value="{{ $miEvaluacion ? $miEvaluacion->score : 0 }}">

                <label style="margin-top: 20px; display: block; font-weight: bold;">Retroalimentación (Opcional)</label>
                <textarea name="feedback" id="feedback" rows="4" 
                          style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 5px;"
                          placeholder="Comentarios..." disabled>{{ $miEvaluacion ? $miEvaluacion->feedback : '' }}</textarea>

                <div class="buttons" style="margin-top: 25px; display: flex; gap: 10px; justify-content: flex-end;">
                    @if($miEvaluacion)
                        <div style="flex-grow: 1; text-align: left; color: #d9534f; font-weight: bold; padding-top: 10px;">
                            ⚠️ Calificación registrada.
                        </div>
                        <button type="button" class="btn cancel" onclick="window.location='{{ route('judge.teams', $equipo->event_id) }}'">Regresar</button>
                    @else
                        {{-- Botón "Habilitar" BLOQUEADO por defecto --}}
                        <button type="button" id="editBtn" class="btn edit btn-locked" disabled>
                            🔒 Descarga el archivo primero
                        </button>
                        
                        <button type="submit" id="saveBtn" class="btn save" disabled>Guardar Evaluación</button>
                        <button type="button" class="btn cancel" onclick="window.location='{{ route('judge.teams', $equipo->event_id) }}'">Cancelar</button>
                    @endif
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
        const feedbackInput = document.getElementById("feedback");
        const downloadMsg = document.getElementById("downloadMsg");
        
        const alreadyScored = @json($miEvaluacion ? true : false);

        // --- CALCULAR SUMA ---
        function calculateTotal(currentInput) {
            let total = 0;
            const inputs = document.querySelectorAll('.score-input');
            if (currentInput) {
                let val = parseFloat(currentInput.value);
                let max = parseFloat(currentInput.dataset.max);
                if (val > max) {
                    currentInput.value = max;
                    currentInput.classList.add('input-error');
                    setTimeout(() => currentInput.classList.remove('input-error'), 500);
                }
                if (val < 0) currentInput.value = 0;
            }
            inputs.forEach(input => {
                let val = parseFloat(input.value) || 0;
                total += val;
            });
            document.getElementById('displayTotal').innerText = total;
            document.getElementById('finalScore').value = total;
        }

        // --- HABILITAR FORMULARIO ---
        const enableGrading = () => {
            document.querySelectorAll('.score-input').forEach(inp => {
                inp.disabled = false;
                inp.style.backgroundColor = "#fff";
            });
            feedbackInput.disabled = false;
            saveBtn.disabled = false;
            if(editBtn) editBtn.style.display = 'none';
        };

        // --- EVENTO DE BOTÓN "EMPEZAR" ---
        if(editBtn) {
            editBtn.addEventListener("click", enableGrading);
        }

        // --- LÓGICA DE DESCARGA OBLIGATORIA ---
        if (downloadBtn) {
            downloadBtn.addEventListener("click", () => {
                // Simulamos que tras 1 segundo de descarga, se habilita el botón
                setTimeout(() => {
                    if (!alreadyScored && editBtn) {
                        // Quitamos el bloqueo
                        editBtn.disabled = false;
                        editBtn.classList.remove('btn-locked');
                        editBtn.innerText = "Empezar a Calificar";
                        editBtn.style.backgroundColor = "#007bff";
                        editBtn.style.color = "white";
                        editBtn.style.cursor = "pointer";
                        
                        // Mensaje de éxito
                        downloadMsg.style.display = "block";
                        downloadBtn.innerHTML = "✅ Archivo descargado";
                    }
                }, 1500); // 1.5 segundos de espera
            });
        }
    </script>

</body>
</html>