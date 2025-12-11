<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Equipo | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="{{ asset('styles/crear-team.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ESTILOS MEJORADOS DIRECTOS PARA ESTA VISTA */
        body {
            background-color: #f3f4f6; /* Fondo gris claro suave */
            font-family: 'Inter', sans-serif;
        }

        .main-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .form-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 40px;
            overflow: hidden;
        }

        .page-title {
            font-family: 'Jomolhari', serif;
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 10px;
            text-align: center;
        }

        .page-subtitle {
            text-align: center;
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 1rem;
        }

        /* GRID DE FORMULARIO */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-card { padding: 25px; }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            color: #111;
            transition: all 0.3s ease;
            box-sizing: border-box; /* Vital para que no se salga */
            background-color: #f9fafb;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-control.readonly {
            background-color: #e5e7eb;
            color: #6b7280;
            cursor: not-allowed;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* EVENTO PRESELECCIONADO */
        .event-badge {
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }

        /* BOTONES */
        .actions-row {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            border-top: 1px solid #f3f4f6;
            padding-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            border: none;
            transition: transform 0.1s, box-shadow 0.2s;
        }

        .btn:active { transform: scale(0.98); }

        .btn-primary {
            background-color: #2563eb; /* Azul */
            color: white;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        }
        .btn-primary:hover { background-color: #1d4ed8; }

        .btn-secondary {
            background-color: #f3f4f6;
            color: #4b5563;
            border: 1px solid #d1d5db;
        }
        .btn-secondary:hover { background-color: #e5e7eb; color: #111; }

        /* VISTA DE ÉXITO */
        .success-card {
            text-align: center;
            padding: 50px 20px;
        }
        .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            display: block;
        }
        .invite-code-box {
            background: #f0fdf4;
            border: 2px dashed #22c55e;
            color: #15803d;
            font-size: 2rem;
            font-weight: 800;
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 400px;
            letter-spacing: 2px;
        }
        .btn-copy {
            background: none;
            border: none;
            color: #2563eb;
            text-decoration: underline;
            cursor: pointer;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }
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
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}
            </div>
        </div>
    </nav>

    <div class="main-container">

        @if(session('equipo_creado'))
            <div class="form-card success-card">
                <span class="success-icon">🎉</span>
                <h1 class="page-title">¡Equipo Creado Exitosamente!</h1>
                <p class="page-subtitle">Tu equipo está listo. Comparte este código con tus compañeros para que se unan.</p>

                <div class="invite-code-box" id="inviteCode">{{ session('equipo_creado') }}</div>
                
                <button class="btn-copy" onclick="copiarCodigo()">📋 Copiar código al portapapeles</button>
                
                <br>
                <button class="btn btn-primary" onclick="window.location.href='{{ route('dashboard.estudiante') }}'">Ir al Dashboard</button>
            </div>

        @else

            <div class="form-card">
                <h1 class="page-title">Crear Nuevo Equipo</h1>
                <p class="page-subtitle">Completa los datos para registrar a tu equipo en el evento.</p>

                @if($errors->any())
                    <div style="background:#fee2e2; color:#b91c1c; padding:15px; border-radius:8px; margin-bottom:25px; border: 1px solid #fecaca;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('teams.store') }}" method="POST">
                    @csrf

                    <div class="form-grid">
                        
                        <div class="col-left">
                            <div class="form-group">
                                <label>Evento Seleccionado</label>
                                @if(isset($eventoPreseleccionado) && $eventoPreseleccionado)
                                    <div class="event-badge">
                                        🏆 {{ $eventoPreseleccionado->title }}
                                    </div>
                                    <input type="hidden" name="event_id" value="{{ $eventoPreseleccionado->id }}">
                                @else
                                    <select name="event_id" class="form-control" required>
                                        <option value="" disabled selected>-- Elige un evento --</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Nombre del Equipo</label>
                                <input type="text" name="name" class="form-control" required placeholder="Ej: Los Innovadores" value="{{ old('name') }}">
                            </div>

                            <div class="form-group">
                                <label>Visibilidad del Equipo</label>
                                <select name="visibility" class="form-control" required>
                                    <option value="public">🌍 Público (Cualquiera puede unirse)</option>
                                    <option value="private">🔒 Privado (Solo con código)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Máximo de Integrantes</label>
                                <input type="number" name="max_members" class="form-control" min="2" max="10" value="5" required>
                            </div>
                        </div>

                        <div class="col-right">
                            <div class="form-group">
                                <label>Líder del Equipo (Tú)</label>
                                <input type="text" class="form-control readonly" value="{{ Auth::user()->name }} {{ Auth::user()->lastname }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Correo de Contacto</label>
                                <input type="email" class="form-control readonly" value="{{ Auth::user()->email }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Requisitos para unirse (Opcional)</label>
                                <textarea name="requirements" class="form-control" rows="5" placeholder="Ej: Buscamos diseñadores, programadores Java, etc...">{{ old('requirements') }}</textarea>
                            </div>
                        </div>

                    </div> <div class="actions-row">
                        <button type="button" class="btn btn-secondary" 
                            onclick="window.location.href='{{ isset($eventoPreseleccionado) ? route('student.event.show', $eventoPreseleccionado->id) : route('dashboard.estudiante') }}'">
                            Cancelar
                        </button>
                        
                        <button type="submit" class="btn btn-primary">
                            ✨ Crear Equipo
                        </button>
                    </div>

                </form>
            </div>
        @endif

    </div>

    <footer class="footer">
        <p class="footer-copy">© {{ date('Y') }} CodeVision</p>
    </footer>

    <script>
        function copiarCodigo() {
            const codigo = document.getElementById("inviteCode").innerText;
            navigator.clipboard.writeText(codigo).then(() => {
                alert("¡Código copiado al portapapeles!");
            });
        }
    </script>

</body>
</html>