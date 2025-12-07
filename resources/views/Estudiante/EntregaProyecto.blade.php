<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Entrega de Proyecto | CodeVision</title>

    <link rel="stylesheet" href="{{ asset('styles/envio.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <style>
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Estilos para feedback del juez */
        .judge-feedback {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 8px;
            color: #856404;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById("user-toggle");
            const menu = document.getElementById("user-menu");

            toggle.addEventListener("click", () => {
                toggle.classList.toggle("active");
                menu.classList.toggle("show");
            });

            document.addEventListener("click", (e) => {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    toggle.classList.remove("active");
                    menu.classList.remove("show");
                }
            });
        });
    </script>
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" class="logo">
            <span class="site-title">CodeVision</span>
        </div>

        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }}
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            <div id="user-menu" class="dropdown">
                <a href="{{ route('dashboard.estudiante') }}">Inicio</a>
                <a href="{{ route('editarperfil') }}">Perfil</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit();">Cerrar sesión</a>
                </form>
            </div>
        </div>
    </nav>



    <main class="container">

        @if (!$equipo)
            <h1 style="color: red;">No perteneces a ningún equipo</h1>
            <p>Primero debes unirte o crear un equipo para subir un proyecto.</p>
            <a href="{{ route('dashboard.estudiante') }}" class="btn salir">Volver al Inicio</a>
        @else
        <h1 id="evento">Entrega del proyecto: {{ $equipo->event->title ?? 'Evento Desconocido' }}</h1>
        <p class="team-name">Equipo: <span id="equipo" style="font-weight: bold;">{{ $equipo->name }}</span></p>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="box">

                @if ($equipo->project_file_path)
                    <div id="entregado">
                        <h3>✅ Proyecto enviado correctamente</h3>
                        <p>Archivo actual: <a href="{{ route('equipos.descargar', $equipo->id) }}" target="_blank"
                                style="color: blue; text-decoration: underline;">Descargar evidencia subida</a></p>

                        <div class="actions" style="margin-top: 20px;">
                            <button class="btn editar" onclick="mostrarFormulario()">Reemplazar Archivo</button>
                            <button class="btn salir"
                                onclick="window.location='{{ route('dashboard.estudiante') }}'">Volver</button>
                        </div>

                        @if ($equipo->evaluations->count() > 0)
                            <div class="judge-feedback">
                                <h3>🎓 Evaluación del Juez</h3>
                                @foreach ($equipo->evaluations as $eval)
                                    <p><strong>Nota:</strong> {{ $eval->score }}/100</p>
                                    <p><strong>Comentarios:</strong>
                                        {{ $eval->feedback ?? 'Sin comentarios adicionales.' }}</p>
                                    <hr style="border: 0; border-top: 1px solid #ffeeba;">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <div id="subida" class="{{ $equipo->project_file_path ? 'hidden' : '' }}">

                    <form action="{{ route('equipos.subir_archivo', $equipo->id) }}" method="POST"
                        enctype="multipart/form-data"> @csrf

                        <label class="file-label" style="display: block; margin-bottom: 20px;">
                            Seleccionar archivo (PDF, ZIP, RAR, DOCX - Max 10MB)
                            <input type="file" name="archivo_proyecto" id="archivo"
                                accept=".pdf,.zip,.rar,.doc,.docx" required onchange="mostrarNombreArchivo()">
                        </label>

                        <p id="preview" class="preview-text" style="margin-bottom: 20px;">Ningún archivo seleccionado
                        </p>

                        <button type="submit" class="btn enviar">Subir y Enviar</button>

                        @if ($equipo->project_file_path)
                            <button type="button" class="btn cancel" onclick="cancelarEdicion()"
                                style="background-color: #666; margin-left: 10px;">Cancelar</button>
                        @endif
                    </form>

                </div>

            </div>
        @endif

    </main>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca.</p>
            </div>
        </div>
        <p class="footer-copy">© {{ date('Y') }} CodeVision</p>
    </footer>

    <script>
        function mostrarNombreArchivo() {
            const input = document.getElementById('archivo');
            const preview = document.getElementById('preview');
            if (input.files.length > 0) {
                preview.innerText = "Archivo seleccionado: " + input.files[0].name;
                preview.style.color = "green";
                preview.style.fontWeight = "bold";
            } else {
                preview.innerText = "Ningún archivo seleccionado";
                preview.style.color = "#666";
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

    <style>
        .hidden {
            display: none;
        }
    </style>

</body>

</html>
