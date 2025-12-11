<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Entrega de Proyecto | CodeVision</title>
    <link rel="stylesheet" href="{{ asset('styles/envio.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        /* Estilos nuevos para los botones de archivo */
        .file-controls { display: flex; gap: 15px; margin-top: 20px; align-items: center; }
        .btn-add-file { background-color: #333; color: white; padding: 10px 20px; border-radius: 5px; cursor: pointer; border: none; font-weight: bold; }
        .btn-send-file { background-color: #28a745; color: white; padding: 10px 20px; border-radius: 5px; cursor: pointer; border: none; font-weight: bold; }
        .btn-send-file:disabled { background-color: #ccc; cursor: not-allowed; }
        .file-name-display { font-style: italic; color: #555; }
        input[type="file"] { display: none; } /* Ocultar input original */
        .hidden { display: none; }
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
            <div id="user-toggle" class="user-name">{{ Auth::user()->name }}</div>
        </div>
    </nav>

    <main class="container">

        @if (!$equipo)
            <h1 style="color: red;">No perteneces a ningún equipo</h1>
            <a href="{{ route('dashboard.estudiante') }}" class="btn salir">Volver</a>
        @else
            <h1 id="evento">Entrega: {{ $equipo->event->title ?? 'Evento' }}</h1>
            <p class="team-name">Equipo: <strong>{{ $equipo->name }}</strong></p>

            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
            @if ($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif

            <div class="box">
                @php
                    $yaCalificado = $equipo->evaluations->count() > 0;
                @endphp

                @if ($equipo->project_file_path)
                    <div id="entregado">
                        <h3>✅ Proyecto Enviado</h3>
                        <p><a href="{{ route('events.download', $equipo->id) }}" class="btn download" style="color:blue; text-decoration:underline;">Descargar archivo actual</a></p>
                        
                        <div class="actions" style="margin-top: 20px;">
                            @if(!$yaCalificado && $equipo->event->is_active)
                                <button class="btn editar" onclick="mostrarFormulario()">Reemplazar Archivo</button>
                            @elseif($yaCalificado)
                                <p style="color: orange; font-weight: bold;">🔒 No puedes modificar el archivo porque ya fue calificado.</p>
                            @endif
                            <button class="btn salir" onclick="window.location='{{ route('dashboard.estudiante') }}'">Volver</button>
                        </div>

                        @if ($yaCalificado)
                            <div class="judge-feedback" style="margin-top: 20px; background:#fff3cd; padding:15px; border-radius:5px;">
                                <h3>🎓 Evaluación</h3>
                                @foreach ($equipo->evaluations as $eval)
                                    <p><strong>Nota:</strong> {{ $eval->score }}/100</p>
                                    <p><strong>Comentarios:</strong> {{ $eval->feedback ?? 'Sin comentarios.' }}</p>
                                    <hr>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                @if($equipo->event->is_active && !$yaCalificado)
                    <div id="subida" class="{{ $equipo->project_file_path ? 'hidden' : '' }}">
                        
                        <form action="{{ route('teams.upload_file', $equipo->id) }}" method="POST" enctype="multipart/form-data"> 
                            @csrf
                            
                            <p>Sube tu proyecto (PDF, ZIP, RAR, DOCX - Max 10MB)</p>
                            
                            <input type="file" name="archivo_proyecto" id="archivo" accept=".pdf,.zip,.rar,.doc,.docx" required onchange="actualizarNombre()">
                            
                            <div class="file-controls">
                                <button type="button" class="btn-add-file" onclick="document.getElementById('archivo').click()">
                                    📂 Agregar Archivo
                                </button>
                                <span id="nombreArchivo" class="file-name-display">Ningún archivo seleccionado</span>
                            </div>

                            <br>

                            <div style="display: flex; gap: 10px;">
                                <button type="submit" class="btn-send-file" id="btnEnviar" disabled>Enviar Archivo 🚀</button>
                                
                                @if ($equipo->project_file_path)
                                    <button type="button" class="btn cancel" onclick="cancelarEdicion()" style="background-color: #666; color:white; border:none; padding:10px; border-radius:5px;">Cancelar</button>
                                @endif
                            </div>

                        </form>
                    </div>
                @elseif(!$equipo->event->is_active)
                    <div class="alert alert-danger">⚠️ El evento ha finalizado. No se reciben más entregas.</div>
                @endif

            </div>
        @endif

    </main>

    <footer class="footer">
        <p class="footer-copy">© {{ date('Y') }} CodeVision</p>
    </footer>

    <script>
        function actualizarNombre() {
            const input = document.getElementById('archivo');
            const display = document.getElementById('nombreArchivo');
            const btnEnviar = document.getElementById('btnEnviar');

            if (input.files.length > 0) {
                display.innerText = input.files[0].name;
                display.style.color = "green";
                display.style.fontWeight = "bold";
                btnEnviar.disabled = false; // Habilitar envío
            } else {
                display.innerText = "Ningún archivo seleccionado";
                btnEnviar.disabled = true;
            }
        }

        function mostrarFormulario() {
            document.getElementById('entregado').classList.add('hidden');
            document.getElementById('subida').classList.remove('hidden');
        }

        function cancelarEdicion() {
            document.getElementById('subida').classList.add('hidden');
            document.getElementById('entregado').classList.remove('hidden');
        }
    </script>

</body>
</html>